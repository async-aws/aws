<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Result;
use AsyncAws\Core\StreamableBody;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ResultGenerator
{
    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * All public classes take a definition as first parameter.
     *
     * @var ServiceDefinition
     */
    private $definition;

    /**
     * @var XmlParser|null
     */
    private $xmlParser;

    public function __construct(FileWriter $fileWriter, ServiceDefinition $definition)
    {
        $this->fileWriter = $fileWriter;
        $this->definition = $definition;
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generate(Operation $operation, string $baseNamespace, bool $root, bool $useTrait)
    {
        $output = $operation->getOutput();
        $this->generateResultClass($baseNamespace, $output->getName(), $root, $useTrait, $operation);
        if ($useTrait) {
            $this->generateOutputTrait($operation, $baseNamespace, $output->getName());
        }
    }

    private function generateOutputTrait(Operation $operation, string $baseNamespace, string $className)
    {
        $traitName = $className . 'Trait';

        $namespace = new PhpNamespace($baseNamespace);
        $trait = $namespace->addTrait($traitName);

        $namespace->addUse(ResponseInterface::class);
        $namespace->addUse(HttpClientInterface::class);

        $isNew = !trait_exists($baseNamespace . '\\' . $traitName);
        $this->resultClassPopulateResult($operation, $className, $isNew, $namespace, $trait);

        $this->fileWriter->write($namespace);
    }

    private function generateResultClass(string $baseNamespace, string $className, bool $root = false, ?bool $useTrait = null, ?Operation $operation = null): void
    {
        $inputShape = $this->definition->getShape($className);

        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass(GeneratorHelper::safeClassName($className));

        if ($root) {
            $namespace->addUse(Result::class);
            $class->addExtend(Result::class);

            $traitName = $baseNamespace . '\\' . $className . 'Trait';
            if ($useTrait) {
                $class->addTrait($traitName);
            } else {
                $namespace->addUse(ResponseInterface::class);
                $namespace->addUse(HttpClientInterface::class);
                $this->resultClassPopulateResult($operation, $className, false, $namespace, $class);
                $this->fileWriter->delete($traitName);
            }
        } else {
            // Named constructor
            $this->resultClassAddNamedConstructor($baseNamespace, $inputShape, $class);
        }

        $this->resultClassAddProperties($baseNamespace, $root, $inputShape, $className, $class, $namespace);
        // should be called After Properties injection
        if ($operation && null !== $pagination = $this->definition->getOperationPagination($operation->getName())) {
            $this->generateOutputPagination($pagination, $className, $baseNamespace, $class);
        }

        $this->fileWriter->write($namespace);
    }

    private function generateOutputPagination(array $pagination, string $className, string $baseNamespace, ClassType $class)
    {
        if (empty($pagination['result_key'])) {
            throw new \RuntimeException('This is not implemented yet');
        }

        $class->addImplement(\IteratorAggregate::class);
        $iteratorBody = '';
        $iteratorTypes = [];
        foreach ((array) $pagination['result_key'] as $resultKey) {
            $iteratorBody .= strtr('yield from $this->PROPERTY_NAME;
            ', [
                'PROPERTY_NAME' => $resultKey,
            ]);
            $resultShapeName = $this->definition->getShape($className)['members'][$resultKey]['shape'];
            $resultShape = $this->definition->getShape($resultShapeName);

            if ('list' !== $resultShape['type']) {
                throw new \RuntimeException('Cannot generate a pagination for a non-iterable result');
            }

            $listShape = $this->definition->getShape($resultShape['member']['shape']);
            if ('structure' !== $listShape['type']) {
                $iteratorTypes[] = $iteratorType = GeneratorHelper::toPhpType($listShape['type']);
            } else {
                $iteratorTypes[] = $iteratorType = GeneratorHelper::safeClassName($resultShape['member']['shape']);
            }

            $getter = 'get' . $resultKey;
            if (!$class->hasMethod($getter)) {
                throw new \RuntimeException(sprintf('Unable to find the method "%s" in "%s"', $getter, $className));
            }
            $getterBody = strtr('
                $this->initialize();

                if ($currentPageOnly) {
                    return $this->PROPERTY_NAME;
                }
                while (true) {
                    yield from $this->PROPERTY_NAME;

                    // TODO load next results
                    break;
                }
            ', [
                'PROPERTY_NAME' => $resultKey,
            ]);
            $method = $class->getMethod($getter);
            $method
                ->setParameters([(new Parameter('currentPageOnly'))->setType('bool')->setDefaultValue(false)])
                ->setReturnType('iterable')
                ->setComment('@param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.')
                ->addComment("@return iterable<$iteratorType>")
                ->setBody($getterBody);
        }

        $iteratorType = implode('|', $iteratorTypes);

        $iteratorBody = strtr('
            $this->initialize();

            while (true) {
                ITERATE_PROPERTIES_CODE

                // TODO load next results
                break;
            }
        ', [
            'ITERATE_PROPERTIES_CODE' => $iteratorBody,
        ]);

        $class->removeMethod('getIterator');
        $class->addMethod('getIterator')
            ->setReturnType(\Traversable::class)
            ->addComment('Iterates over ' . implode(' then ', (array) $pagination['result_key']))
            ->addComment("@return \Traversable<$iteratorType>")
            ->setBody($iteratorBody)
        ;
    }

    private function resultClassAddNamedConstructor(string $baseNamespace, Shape $inputShape, ClassType $class): void
    {
        $class->addMethod('create')->setStatic(true)->setReturnType('self')->setBody(
            <<<PHP
return \$input instanceof self ? \$input : new self(\$input);
PHP
        )->addParameter('input');

        // We need a constructor
        $constructor = $class->addMethod('__construct');
        $constructor->addComment('@param array{');
        GeneratorHelper::addMethodComment($this->definition, $constructor, $inputShape, $baseNamespace);
        $constructor->addComment('} $input');
        $constructor->addParameter('input')->setType('array')->setDefaultValue([]);

        $constructorBody = '';
        foreach ($inputShape['members'] as $name => $data) {
            $parameterType = $data['shape'];
            $memberShape = $this->definition->getShape($parameterType);
            if ('structure' === $memberShape['type']) {
                $this->generateResultClass($baseNamespace, $parameterType);
                $constructorBody .= sprintf('$this->%s = isset($input["%s"]) ? %s::create($input["%s"]) : null;' . "\n", $name, $name, GeneratorHelper::safeClassName($parameterType), $name);
            } elseif ('list' === $memberShape['type']) {
                // Check if this is a list of objects
                $listItemShapeName = $memberShape['member']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    // todo this is needed in Input but useless in Result
                    $this->generateResultClass($baseNamespace, $listItemShapeName);
                    $constructorBody .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, GeneratorHelper::safeClassName($listItemShapeName), $name);
                } else {
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? [];' . "\n", $name, $name);
                }
            } elseif ('map' === $memberShape['type']) {
                // Check if this is a list of objects
                $listItemShapeName = $memberShape['value']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    // todo this is needed in Input but useless in Result
                    $this->generateResultClass($baseNamespace, $listItemShapeName);
                    $constructorBody .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, GeneratorHelper::safeClassName($listItemShapeName), $name);
                } else {
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? [];' . "\n", $name, $name);
                }
            } else {
                $constructorBody .= sprintf('$this->%s = $input["%s"] ?? null;' . "\n", $name, $name);
            }
        }
        $constructor->setBody($constructorBody);
    }

    /**
     * Add properties and getters.
     */
    private function resultClassAddProperties(string $baseNamespace, bool $root, ?Shape $inputShape, string $className, ClassType $class, PhpNamespace $namespace): void
    {
        $members = $inputShape['members'];
        foreach ($members as $name => $data) {
            $nullable = $returnType = null;
            $property = $class->addProperty($name)->setPrivate();
            if (null !== $propertyDocumentation = $this->definition->getParameterDocumentation($className, $name, $data['shape'])) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            $parameterType = $members[$name]['shape'];
            $memberShape = $this->definition->getShape($parameterType);

            if ('structure' === $memberShape['type']) {
                $this->generateResultClass($baseNamespace, $parameterType);
                $parameterType = $baseNamespace . '\\' . GeneratorHelper::safeClassName($parameterType);
            } elseif ('map' === $memberShape['type']) {
                $mapKeyShape = $this->definition->getShape($memberShape['key']['shape']);

                if ('string' !== $mapKeyShape['type']) {
                    throw new \RuntimeException('Complex maps are not supported');
                }
                $parameterType = 'array';
                $nullable = false;
            } elseif ('list' === $memberShape['type']) {
                $parameterType = 'array';
                $nullable = false;
                $property->setValue([]);

                // Check if this is a list of objects
                $listItemShapeName = $memberShape['member']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    $this->generateResultClass($baseNamespace, $listItemShapeName);
                    $returnType = GeneratorHelper::safeClassName($listItemShapeName);
                } else {
                    $returnType = GeneratorHelper::toPhpType($type);
                }
            } elseif ($data['streaming'] ?? false) {
                $parameterType = StreamableBody::class;
                $namespace->addUse(StreamableBody::class);
                $nullable = false;
            } else {
                $parameterType = GeneratorHelper::toPhpType($memberShape['type']);
            }

            $callInitialize = '';
            if ($root) {
                $callInitialize = <<<PHP
\$this->initialize();
PHP;
            }

            $method = $class->addMethod('get' . $name)
                ->setReturnType($parameterType)
                ->setBody(
                    <<<PHP
$callInitialize
return \$this->{$name};
PHP
                );

            $nullable = $nullable ?? !\in_array($name, $inputShape['required'] ?? []);
            if ($returnType) {
                $method->addComment('@return ' . $returnType . ('array' === $parameterType ? '[]' : ''));
            }
            $method->setReturnNullable($nullable);
        }
    }

    private function resultClassPopulateResult(Operation $operation, string $className, bool $isNew, PhpNamespace $namespace, ClassType $class): void
    {
        $shape = $this->definition->getShape($className);

        // Parse headers
        $nonHeaders = [];
        $body = '';
        foreach ($shape['members'] as $name => $member) {
            if (($member['location'] ?? null) !== 'header') {
                $nonHeaders[$name] = $member;

                continue;
            }

            $locationName = strtolower($member['locationName'] ?? $name);
            $memberShape = $this->definition->getShape($member['shape']);
            if ('timestamp' === $memberShape['type']) {
                $body .= "\$this->$name = isset(\$headers['{$locationName}'][0]) ? new \DateTimeImmutable(\$headers['{$locationName}'][0]) : null;\n";
            } else {
                if (null !== $constant = GeneratorHelper::getFilterConstantFromType($memberShape['type'])) {
                    // Convert to proper type
                    $body .= "\$this->$name = isset(\$headers['{$locationName}'][0]) ? filter_var(\$headers['{$locationName}'][0], {$constant}) : null;\n";
                } else {
                    $body .= "\$this->$name = \$headers['{$locationName}'][0] ?? null;\n";
                }
            }
        }

        foreach ($nonHeaders as $name => $member) {
            if (($member['location'] ?? null) !== 'headers') {
                continue;
            }
            unset($nonHeaders[$name]);

            $locationName = strtolower($member['locationName'] ?? $name);
            $length = \strlen($locationName);
            $body .= <<<PHP
\$this->$name = [];
foreach (\$headers as \$name => \$value) {
    if (substr(\$name, 0, {$length}) === '{$locationName}') {
        \$this->{$name}[\$name] = \$value[0];
    }
}

PHP;
        }

        $comment = '';
        if ($isNew) {
            $comment = "// TODO Verify correctness\n";
        }

        // Prepend with $headers = ...
        if (!empty($body)) {
            $body = <<<PHP
\$headers = \$response->getHeaders(false);

$comment
PHP
                . $body;
        }

        $body .= "\n";
        $xmlParser = '';
        if (isset($shape['payload'])) {
            $name = $shape['payload'];
            $member = $shape['members'][$name];
            if (true === ($member['streaming'] ?? false)) {
                // Make sure we can stream this.
                $namespace->addUse(StreamableBody::class);
                $body .= strtr('
                    if (null !== $httpClient) {
                        $this->PROPERTY_NAME = new StreamableBody($httpClient->stream($response));
                    } else {
                        $this->PROPERTY_NAME = $response->getContent(false);
                    }
                ', ['PROPERTY_NAME' => $name]);
            } else {
                $xmlParser = $this->parseXml($shape);
            }
        } else {
            $xmlParser = $this->parseXml($shape);
        }

        if (!empty($xmlParser)) {
            $body .= "\$data = new \SimpleXMLElement(\$response->getContent(false));";
            $wrapper = $operation->getOutputResultWrapper();
            if (null !== $wrapper) {
                $body .= "\$data = \$data->$wrapper;\n";
            }
            $body .= "\n" . $xmlParser;
        }

        $method = $class->addMethod('populateResult')
            ->setReturnType('void')
            ->setProtected()
            ->setBody($body);
        $method->addParameter('response')->setType(ResponseInterface::class);
        $method->addParameter('httpClient')->setType(HttpClientInterface::class)->setNullable(true);
    }

    private function parseXml(Shape $shape): string
    {
        if (null === $this->xmlParser) {
            $this->xmlParser = new XmlParser();
        }

        return $this->xmlParser->parseXmlResponseRoot($this->definition, $shape);
    }
}
