<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Result;
use AsyncAws\Core\StreamableBody;
use AsyncAws\Core\StreamableBodyInterface;
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
    public function generate(Operation $operation, string $service, string $baseNamespace, bool $root, bool $useTrait)
    {
        $output = $operation->getOutput();
        $this->generateResultClass($service, $baseNamespace, $output->getName(), $root, $useTrait, $operation);
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

    private function generateResultClass(string $service, string $baseNamespace, string $className, bool $root = false, ?bool $useTrait = null, ?Operation $operation = null): void
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
                $this->fileWriter->deleteClass($traitName);
            }
        } else {
            // Named constructor
            $this->resultClassAddNamedConstructor($service, $baseNamespace, $inputShape, $class);
        }

        $this->resultClassAddProperties($service, $baseNamespace, $root, $inputShape, $className, $class, $namespace);
        // should be called After Properties injection
        if ($operation && null !== $pagination = $this->definition->getOperationPagination($operation->getName())) {
            $this->generateOutputPagination($pagination, $className, $baseNamespace, $service, $operation, $namespace, $class);
        }

        $this->fileWriter->write($namespace);
    }

    private function generateOutputPagination(array $pagination, string $className, string $baseNamespace, string $service, Operation $operation, PhpNamespace $namespace, ClassType $class)
    {
        if (empty($pagination['result_key'])) {
            throw new \RuntimeException('This is not implemented yet');
        }

        $class->addImplement(\IteratorAggregate::class);
        $iteratorBody = '';
        $iteratorTypes = [];
        foreach ((array) $pagination['result_key'] as $resultKey) {
            $singlePage = empty($pagination['output_token']);
            $iteratorBody .= strtr('yield from $page->PROPERTY_ACCESSOR(SINGLE_PAGE_FLAG);
            ', [
                'PROPERTY_ACCESSOR' => 'get' . $resultKey,
                'SINGLE_PAGE_FLAG' => $singlePage ? '' : 'true',
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

            $method = $class->getMethod($getter);
            $method
                ->setReturnType('iterable')
                ->setComment('')
            ;
            if ($singlePage) {
                $method
                    ->setBody(strtr('
                        $this->initialize();
                        return $this->PROPERTY_NAME;
                    ', [
                        'PROPERTY_NAME' => $resultKey,
                    ]));
            } else {
                $method
                    ->setParameters([(new Parameter('currentPageOnly'))->setType('bool')->setDefaultValue(false)])
                    ->addComment('@param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.')
                    ->setBody(strtr('
                        if ($currentPageOnly) {
                            $this->initialize();
                            yield from $this->PROPERTY_NAME;

                            return;
                        }

                        PAGE_LOADER_CODE
                    ', [
                        'PROPERTY_NAME' => $resultKey,
                        'PAGE_LOADER_CODE' => $this->generateOutputPaginationLoader(
                            strtr('yield from $page->PROPERTY_ACCESSOR(true);', ['PROPERTY_ACCESSOR' => 'get' . $resultKey]),
                            $pagination, $namespace, $baseNamespace, $service, $operation
                        ),
                    ]));
            }

            $method
                ->addComment("@return iterable<$iteratorType>");
        }

        $iteratorType = implode('|', $iteratorTypes);

        $class->removeMethod('getIterator');
        $class->addMethod('getIterator')
            ->setReturnType(\Traversable::class)
            ->addComment('Iterates over ' . implode(' then ', (array) $pagination['result_key']))
            ->addComment("@return \Traversable<$iteratorType>")
            ->setBody($this->generateOutputPaginationLoader($iteratorBody, $pagination, $namespace, $baseNamespace, $service, $operation))
        ;
    }

    private function generateOutputPaginationLoader(string $iterator, array $pagination, PhpNamespace $namespace, string $baseNamespace, string $service, Operation $operation): string
    {
        if (empty($pagination['output_token'])) {
            return \strtr($iterator, ['$page->' => '$this->']);
        }

        $inputToken = (array) $pagination['input_token'];
        $outputToken = (array) $pagination['output_token'];
        if (empty($pagination['more_results'])) {
            $moreBody = '';
            foreach ($outputToken as $index => $property) {
                $moreBody .= strtr('
                    if (!$page->MORE_ACCESSOR()) {
                        break;
                    }
                ', [
                    'MORE_ACCESSOR' => 'get' . trim(explode('||', $property)[0]),
                ]);
            }
        } else {
            $moreBody = strtr('
                if (!$page->MORE_ACCESSOR()) {
                    break;
                }
            ', [
                'MORE_ACCESSOR' => 'get' . $pagination['more_results'],
            ]);
        }
        $setter = '';
        foreach ($inputToken as $index => $property) {
            $setter .= strtr('
                $input->SETTER($page->GETTER());
            ', [
                'SETTER' => 'set' . $property,
                'GETTER' => 'get' . trim(explode('||', $outputToken[$index])[0]),
            ]);
        }

        $baseNamespace = substr($baseNamespace, 0, \strrpos($baseNamespace, '\\'));
        $inputShape = $operation->getInput();
        $inputSafeClassName = GeneratorHelper::safeClassName($inputShape->getName());
        $namespace->addUse($baseNamespace . '\\Input\\' . $inputSafeClassName);

        $clientClassName = $service . 'Client';
        $namespace->addUse($baseNamespace . '\\' . $clientClassName);

        return strtr('
            if (!$this->client instanceOf CLIENT_CLASSNAME) {
                throw new \InvalidArgumentException(\'missing client injected in paginated result\');
            }
            $input = $this->lastRequest;
            if (!$input instanceOf INPUT_CLASSNAME) {
                throw new \InvalidArgumentException(\'missing last request injected in paginated result\');
            }
            $page = $this;
            while (true) {
                ITERATE_PROPERTIES_CODE
                CHECK_MORE_RESULT_CODE
                SET_TOKEN_CODE
                $page = $this->client->OPERATION_NAME($input);
            }
        ', [
            'CLIENT_CLASSNAME' => $clientClassName,
            'INPUT_CLASSNAME' => $inputSafeClassName,
            'ITERATE_PROPERTIES_CODE' => $iterator,
            'CHECK_MORE_RESULT_CODE' => $moreBody,
            'SET_TOKEN_CODE' => $setter,
            'OPERATION_NAME' => $operation->getName(),
        ]);
    }

    private function resultClassAddNamedConstructor(string $service, string $baseNamespace, Shape $inputShape, ClassType $class): void
    {
        $class->addMethod('create')->setStatic(true)->setReturnType('self')->setBody(
            <<<PHP
return \$input instanceof self ? \$input : new self(\$input);
PHP
        )->addParameter('input');

        // We need a constructor
        $constructor = $class->addMethod('__construct');
        $constructor->addComment('@param array{');
        foreach (GeneratorHelper::addMethodComment($this->definition, $inputShape, $baseNamespace, false, true) as $comment) {
            $constructor->addComment($comment);
        }
        $constructor->addComment('} $input');
        $constructor->addParameter('input')->setType('array');

        $constructorBody = '';
        foreach ($inputShape['members'] as $name => $data) {
            $parameterType = $data['shape'];
            $memberShape = $this->definition->getShape($parameterType);
            if ('structure' === $memberShape['type']) {
                $this->generateResultClass($service, $baseNamespace, $parameterType);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? SAFE_CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $name, 'SAFE_CLASS' => GeneratorHelper::safeClassName($parameterType)]);
            } elseif ('list' === $memberShape['type']) {
                // Check if this is a list of objects
                $listItemShapeName = $memberShape['member']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    // todo this is needed in Input but useless in Result
                    $this->generateResultClass($service, $baseNamespace, $listItemShapeName);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return SAFE_CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $name, 'SAFE_CLASS' => GeneratorHelper::safeClassName($listItemShapeName)]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $name]);
                }
            } elseif ('map' === $memberShape['type']) {
                // Check if this is a list of objects
                $listItemShapeName = $memberShape['value']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    // todo this is needed in Input but useless in Result
                    $this->generateResultClass($service, $baseNamespace, $listItemShapeName);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return SAFE_CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $name, 'SAFE_CLASS' => GeneratorHelper::safeClassName($listItemShapeName)]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $name]);
                }
            } else {
                $constructorBody .= strtr('$this->NAME = $input["NAME"];' . "\n", ['NAME' => $name]);
            }
        }
        $constructor->setBody($constructorBody);
    }

    /**
     * Add properties and getters.
     */
    private function resultClassAddProperties(string $service, string $baseNamespace, bool $root, ?Shape $inputShape, string $className, ClassType $class, PhpNamespace $namespace): void
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
                $this->generateResultClass($service, $baseNamespace, $parameterType);
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
                    $this->generateResultClass($service, $baseNamespace, $listItemShapeName);
                    $returnType = GeneratorHelper::safeClassName($listItemShapeName);
                } else {
                    $returnType = GeneratorHelper::toPhpType($type);
                }
            } elseif ($data['streaming'] ?? false) {
                $parameterType = StreamableBodyInterface::class;
                $namespace->addUse(StreamableBodyInterface::class);
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
                $body .= strtr('$this->PROPERTY_NAME = new StreamableBody($httpClient->stream($response));', ['PROPERTY_NAME' => $name]);
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
        $method->addParameter('httpClient')->setType(HttpClientInterface::class);
    }

    private function parseXml(Shape $shape): string
    {
        if (null === $this->xmlParser) {
            $this->xmlParser = new XmlParser();
        }

        return $this->xmlParser->parseXmlResponseRoot($this->definition, $shape);
    }
}
