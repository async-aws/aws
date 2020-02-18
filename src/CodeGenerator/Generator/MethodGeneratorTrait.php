<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Result;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
trait MethodGeneratorTrait
{
    /**
     * Generate classes for the input.
     */
    private function generateInputClass(string $service, Operation $operation, string $baseNamespace, StructureShape $inputShape, bool $root = false)
    {
        $members = $inputShape->getMembers();
        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass(GeneratorHelper::safeClassName($inputShape->getName()));

        $constructorBody = '';
        $requiredProperties = [];

        foreach ($members as $member) {
            $returnType = null;
            $memberShape = $member->getShape();
            $nullable = true;
            if ($memberShape instanceof StructureShape) {
                $this->generateInputClass($service, $operation, $baseNamespace, $memberShape);
                $memberClassName = GeneratorHelper::safeClassName($memberShape->getName());
                $returnType = $baseNamespace . '\\' . $memberClassName;
                $parameterType = $memberShape->getName();
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? SAFE_CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => $memberClassName]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();
                $nullable = false;

                // Is this a list of objects?
                if ($listMemberShape instanceof StructureShape) {
                    $this->generateInputClass($service, $operation, $baseNamespace, $listMemberShape);
                    $listMemberClassName = GeneratorHelper::safeClassName($listMemberShape->getName());

                    $parameterType = $listMemberClassName . '[]';
                    $returnType = $baseNamespace . '\\' . $listMemberClassName;
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return SAFE_CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => GeneratorHelper::safeClassName($listMemberClassName)]);
                } elseif ($listMemberShape instanceof ListShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    // It is a scalar, like a string
                    $parameterType = GeneratorHelper::toPhpType($listMemberShape->getType()) . '[]';
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($member->isStreaming()) {
                $parameterType = 'string|resource|\Closure';
                $returnType = null;
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            } else {
                $returnType = $parameterType = GeneratorHelper::toPhpType($memberShape->getType());
                if ('\DateTimeImmutable' !== $parameterType) {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
                } else {
                    $constructorBody .= strtr('$this->NAME = !isset($input["NAME"]) ? null : ($input["NAME"] instanceof \DateTimeInterface ? $input["NAME"] : new \DateTimeImmutable($input["NAME"]));' . "\n", ['NAME' => $member->getName()]);
                    $parameterType = $returnType = '\DateTimeInterface';
                }
            }

            $property = $class->addProperty($member->getName())->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if ($member->isRequired()) {
                $requiredProperties[] = $member->getName();
                $property->addComment('@required');
            }
            $property->addComment('@var ' . $parameterType . ($nullable ? '|null' : ''));

            $returnType = '[]' === substr($parameterType, -2) ? 'array' : $returnType;

            $class->addMethod('get' . $member->getName())
                ->setReturnType($returnType)
                ->setReturnNullable($nullable)
                ->setBody(strtr('return $this->NAME;', ['NAME' => $member->getName()]));

            $class->addMethod('set' . $member->getName())
                ->setReturnType('self')
                ->setBody(strtr('
                    $this->NAME = $value;
                    return $this;
                ', [
                    'NAME' => $member->getName(),
                ]))
                ->addParameter('value')->setType($returnType)->setNullable($nullable)
            ;
        }

        // Add named constructor
        $class->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody(strtr('
                return $input instanceof self ? $input : new self(ARGS);
            ', ['ARGS' => empty($constructorBody) ? '' : '$input']))
            ->addParameter('input');

        if (!empty($constructorBody)) {
            $constructor = $class->addMethod('__construct');
            if ($root && null !== $documentationUrl = $operation->getDocumentationUrl()) {
                $constructor->addComment('@see ' . $documentationUrl);
            }

            $constructor->addComment(GeneratorHelper::getParamDocblock($inputShape, $baseNamespace, null, $root));

            $inputParameter = $constructor->addParameter('input')->setType('array');
            if ($root || empty($inputShape->getRequired())) {
                $inputParameter->setDefaultValue([]);
            }
            $constructor->setBody($constructorBody);
        }
        if ($root) {
            $this->inputClassRequestGetters($inputShape, $class, $operation);
        }

        // Add validate()
        $namespace->addUse(InvalidArgument::class);
        $validateBody = '';

        if (!empty($requiredProperties)) {
            $validateBody = strtr('
                foreach (["PROPERTIES"] as $name) {
                    if (null === $this->$name) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "%s" when validating the "%s". The value cannot be null.\', $name, __CLASS__));
                    }
                }
            ', [
                'PROPERTIES' => implode('", "', $requiredProperties),
            ]);
        }

        foreach ($members as $member) {
            $memberShape = $member->getShape();
            if ($memberShape instanceof StructureShape) {
                $validateBody .= 'if ($this->' . $member->getName() . ') $this->' . $member->getName() . '->validate();' . "\n";
            } elseif ($memberShape instanceof ListShape) {
                if ($memberShape->getMember()->getShape() instanceof StructureShape) {
                    $validateBody .= 'foreach ($this->' . $member->getName() . ' as $item) $item->validate();' . "\n";
                }
            }
        }

        $class->addMethod('validate')->setPublic()->setReturnType('void')->setBody(empty($validateBody) ? '// There are no required properties' : $validateBody);

        $this->fileWriter->write($namespace);
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassType $class, Operation $operation): void
    {
        foreach (['header' => '$headers', 'querystring' => '$query', 'payload' => '$payload'] as $requestPart => $varName) {
            $body[$requestPart] = $varName . ' = [];' . "\n";
            if ('payload' === $requestPart) {
                $body[$requestPart] = $varName . " = ['Action' => '{$operation->getName()}', 'Version' => '{$operation->getService()->getApiVersion()}'];\n";
            }
            foreach ($inputShape->getMembers() as $member) {
                // If location is not specified, it will go in the request body.
                if ($requestPart === ($member->getLocation() ?? 'payload')) {
                    $body[$requestPart] .= 'if ($this->' . $member->getName() . ' !== null) ' . $varName . '["' . ($member->getLocationName() ?? $member->getName()) . '"] = $this->' . $member->getName() . ';' . "\n";
                }
            }

            $body[$requestPart] .= 'return ' . $varName . ';' . "\n";
        }

        $class->addMethod('requestHeaders')->setReturnType('array')->setBody($body['header']);
        $class->addMethod('requestQuery')->setReturnType('array')->setBody($body['querystring']);
        $class->addMethod('requestBody')->setReturnType('array')->setBody($body['payload']);

        foreach ($inputShape->getMembers() as $member) {
            if ('uri' === $member->getLocation()) {
                if (!isset($body['uri'])) {
                    $body['uri'] = '$uri = [];' . "\n";
                }
                $body['uri'] .= \strtr('$uri["LOCATION"] = $this->NAME ?? "";', ['NAME' => $member->getName(), 'LOCATION' => $member->getLocationName()]);
            }
        }

        $body['uri'] = $body['uri'] ?? '';
        $body['uri'] .= 'return "' . str_replace(['{', '+}', '}'], ['{$uri[\'', '}', '\']}'], $operation->getHttpRequestUri()) . '";';

        $class->addMethod('requestUri')->setReturnType('string')->setBody($body['uri']);
    }
}
