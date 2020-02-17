<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Pagination;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Exception\LogicException;
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
     * @var XmlParser|null
     */
    private $xmlParser;

    public function __construct(FileWriter $fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generate(Operation $operation, string $service, string $baseNamespace, bool $root, bool $useTrait)
    {
        if (null === $output = $operation->getOutput()) {
            throw new LogicException(sprintf('The operation "%s" does not have any output to generate', $operation->getName()));
        }

        $this->generateResultClass($service, $baseNamespace, $output, $root, $useTrait, $operation);
        if ($useTrait) {
            $this->generateOutputTrait($operation, $baseNamespace, $output);
        }
    }

    private function generateOutputTrait(Operation $operation, string $baseNamespace, StructureShape $shape)
    {
        $traitName = $shape->getName() . 'Trait';

        $namespace = new PhpNamespace($baseNamespace);
        $trait = $namespace->addTrait($traitName);

        $namespace->addUse(ResponseInterface::class);
        $namespace->addUse(HttpClientInterface::class);

        $this->resultClassPopulateResult($operation, $shape, $namespace, $trait);

        $this->fileWriter->write($namespace);
    }

    private function generateResultClass(string $service, string $baseNamespace, StructureShape $shape, bool $root = false, ?bool $useTrait = null, ?Operation $operation = null): void
    {
        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass(GeneratorHelper::safeClassName($shape->getName()));

        if ($root) {
            $namespace->addUse(Result::class);
            $class->addExtend(Result::class);

            $traitName = $baseNamespace . '\\' . $shape->getName() . 'Trait';
            if ($useTrait) {
                $class->addTrait($traitName);
            } else {
                $namespace->addUse(ResponseInterface::class);
                $namespace->addUse(HttpClientInterface::class);
                $this->resultClassPopulateResult($operation, $shape, $namespace, $class);
                $this->fileWriter->deleteClass($traitName);
            }
        } else {
            // Named constructor
            $this->resultClassAddNamedConstructor($service, $baseNamespace, $shape, $class);
        }

        $this->resultClassAddProperties($service, $baseNamespace, $root, $shape, $class, $namespace);
        // must be called after Properties injection
        if ($operation && null !== $pagination = $operation->getPagination()) {
            $this->generateOutputPagination($pagination, $shape, $baseNamespace, $service, $operation, $namespace, $class);
        }

        $this->fileWriter->write($namespace);
    }

    private function generateOutputPagination(Pagination $pagination, StructureShape $shape, string $baseNamespace, string $service, Operation $operation, PhpNamespace $namespace, ClassType $class)
    {
        $class->addImplement(\IteratorAggregate::class);
        $iteratorBody = '';
        $iteratorTypes = [];
        foreach ($pagination->getResultkey() as $resultKey) {
            $singlePage = empty($pagination->getOutputToken());
            $iteratorBody .= strtr('yield from $page->PROPERTY_ACCESSOR(SINGLE_PAGE_FLAG);
            ', [
                'PROPERTY_ACCESSOR' => 'get' . $resultKey,
                'SINGLE_PAGE_FLAG' => $singlePage ? '' : 'true',
            ]);
            $resultShape = $shape->getMember($resultKey)->getShape();

            if (!$resultShape instanceof ListShape) {
                throw new \RuntimeException('Cannot generate a pagination for a non-iterable result');
            }

            $listShape = $resultShape->getMember()->getShape();
            if ($listShape instanceof StructureShape) {
                $iteratorTypes[] = $iteratorType = GeneratorHelper::safeClassName($listShape->getName());
            } else {
                $iteratorTypes[] = $iteratorType = GeneratorHelper::toPhpType($listShape->getType());
            }

            $getter = 'get' . $resultKey;
            if (!$class->hasMethod($getter)) {
                throw new \RuntimeException(sprintf('Unable to find the method "%s" in "%s"', $getter, $shape->getName()));
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
            ->addComment('Iterates over ' . implode(' then ', $pagination->getResultkey()))
            ->addComment("@return \Traversable<$iteratorType>")
            ->setBody($this->generateOutputPaginationLoader($iteratorBody, $pagination, $namespace, $baseNamespace, $service, $operation))
        ;
        if (!empty($pagination->getOutputToken())) {
            $class->addProperty('prefetch')->setVisibility(ClassType::VISIBILITY_PRIVATE)->setComment('@var self[]')->setValue([]);
            $class->removeMethod('__destruct');
            $class->addMethod('__destruct')
                ->setBody('
                    while (!empty($this->prefetch)) {
                        array_shift($this->prefetch)->cancel();
                    }
                ');
        }
    }

    private function generateOutputPaginationLoader(string $iterator, Pagination $pagination, PhpNamespace $namespace, string $baseNamespace, string $service, Operation $operation): string
    {
        if (empty($pagination->getOutputToken())) {
            return \strtr($iterator, ['$page->' => '$this->']);
        }

        $inputToken = $pagination->getInputToken();
        $outputToken = $pagination->getOutputToken();
        if (null === $moreResult = $pagination->getMoreResults()) {
            $moreCondition = '';
            foreach ($outputToken as $index => $property) {
                $moreCondition .= strtr('$page->MORE_ACCESSOR()', [
                    'MORE_ACCESSOR' => 'get' . trim(explode('||', $property)[0]),
                ]);
            }
        } else {
            $moreCondition = strtr('$page->MORE_ACCESSOR()', [
                'MORE_ACCESSOR' => 'get' . $moreResult,
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
            if (!$this->awsClient instanceOf CLIENT_CLASSNAME) {
                throw new \InvalidArgumentException(\'missing client injected in paginated result\');
            }
            if (!$this->request instanceOf INPUT_CLASSNAME) {
                throw new \InvalidArgumentException(\'missing last request injected in paginated result\');
            }
            $input = clone $this->request;
            $page = $this;
            while (true) {
                if (MORE_CONDITION) {
                    SET_TOKEN_CODE
                    $nextPage = $this->awsClient->OPERATION_NAME($input);
                    $this->prefetch[spl_object_hash($nextPage)] = $nextPage;
                } else {
                    $nextPage = null;
                }

                ITERATE_PROPERTIES_CODE

                if ($nextPage === null) {
                    break;
                }

                unset($this->prefetch[spl_object_hash($nextPage)]);
                $page = $nextPage;
            }
        ', [
            'CLIENT_CLASSNAME' => $clientClassName,
            'INPUT_CLASSNAME' => $inputSafeClassName,
            'ITERATE_PROPERTIES_CODE' => $iterator,
            'MORE_CONDITION' => $moreCondition,
            'SET_TOKEN_CODE' => $setter,
            'OPERATION_NAME' => $operation->getName(),
        ]);
    }

    private function resultClassAddNamedConstructor(string $service, string $baseNamespace, StructureShape $shape, ClassType $class): void
    {
        $class->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody('return $input instanceof self ? $input : new self($input);')
            ->addParameter('input');

        // We need a constructor
        $constructor = $class->addMethod('__construct');
        $constructor->addComment('@param array{');
        foreach (GeneratorHelper::addMethodComment($shape, $baseNamespace, false, true) as $comment) {
            $constructor->addComment($comment);
        }
        $constructor->addComment('} $input');
        $constructor->addParameter('input')->setType('array');

        $constructorBody = '';
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            $parameterType = $memberShape->getName();
            if ($memberShape instanceof StructureShape) {
                $this->generateResultClass($service, $baseNamespace, $memberShape);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? SAFE_CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => GeneratorHelper::safeClassName($parameterType)]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // Check if this is a list of objects
                if ($listMemberShape instanceof StructureShape) {
                    $this->generateResultClass($service, $baseNamespace, $listMemberShape);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return SAFE_CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => GeneratorHelper::safeClassName($listMemberShape->getName())]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                if ($mapValueShape instanceof StructureShape) {
                    $this->generateResultClass($service, $baseNamespace, $mapValueShape);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return SAFE_CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => GeneratorHelper::safeClassName($mapValueShape->getName())]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } else {
                $constructorBody .= strtr('$this->NAME = $input["NAME"];' . "\n", ['NAME' => $member->getName()]);
            }
        }
        $constructor->setBody($constructorBody);
    }

    /**
     * Add properties and getters.
     */
    private function resultClassAddProperties(string $service, string $baseNamespace, bool $root, StructureShape $shape, ClassType $class, PhpNamespace $namespace): void
    {
        foreach ($shape->getMembers() as $member) {
            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $class->addProperty($member->getName())->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if ($memberShape instanceof StructureShape) {
                $this->generateResultClass($service, $baseNamespace, $memberShape);
                $parameterType = $baseNamespace . '\\' . GeneratorHelper::safeClassName($memberShape->getName());
            } elseif ($memberShape instanceof MapShape) {
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }

                $parameterType = 'array';
                $nullable = false;
                $property->setValue([]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                $parameterType = 'array';
                $nullable = false;
                $property->setValue([]);

                // Check if this is a list of objects
                if ($listMemberShape instanceof StructureShape) {
                    $this->generateResultClass($service, $baseNamespace, $listMemberShape);
                    $returnType = GeneratorHelper::safeClassName($listMemberShape->getName());
                } else {
                    $returnType = GeneratorHelper::toPhpType($listMemberShape->getType());
                }
            } elseif ($member->isStreaming()) {
                $parameterType = StreamableBodyInterface::class;
                $namespace->addUse(StreamableBodyInterface::class);
                $nullable = false;
            } else {
                $parameterType = GeneratorHelper::toPhpType($memberShape->getType());
            }

            $method = $class->addMethod('get' . $member->getName())
                ->setReturnType($parameterType)
                ->setBody(strtr('
                    INITIALIZE_CODE

                    return $this->NAME;
                ', [
                    'INITIALIZE_CODE' => $root ? '$this->initialize();' : '',
                    'NAME' => $member->getName(),
                ]));

            $nullable = $nullable ?? !$member->isRequired();
            if ($returnType) {
                $method->addComment('@return ' . $returnType . ('array' === $parameterType ? '[]' : ''));
            }
            $method->setReturnNullable($nullable);
        }
    }

    private function resultClassPopulateResult(Operation $operation, StructureShape $shape, PhpNamespace $namespace, ClassType $class): void
    {
        // Parse headers
        $nonHeaders = [];
        $body = '';
        foreach ($shape->getMembers() as $member) {
            if ('header' !== $member->getLocation()) {
                $nonHeaders[$member->getName()] = $member;

                continue;
            }

            $locationName = strtolower($member->getLocationName() ?? $member->getName());
            $memberShape = $member->getShape();
            if ('timestamp' === $memberShape->getType()) {
                $body .= strtr('$this->NAME = isset($headers["LOCATION_NAME"][0]) ? new \DateTimeImmutable($headers["LOCATION_NAME"][0]) : null;' . "\n", [
                    'NAME' => $member->getName(),
                    'LOCATION_NAME' => $locationName,
                ]);
            } else {
                if (null !== $constant = GeneratorHelper::getFilterConstantFromType($memberShape->getType())) {
                    $body .= strtr('$this->NAME = isset($headers["LOCATION_NAME"][0]) ? filter_var($headers["LOCATION_NAME"][0], FILTER) : null;' . "\n", [
                        'NAME' => $member->getName(),
                        'LOCATION_NAME' => $locationName,
                        'FILTER' => $constant,
                    ]);
                } else {
                    $body .= strtr('$this->NAME = $headers["LOCATION_NAME"][0] ?? null;' . "\n", [
                        'NAME' => $member->getName(),
                        'LOCATION_NAME' => $locationName,
                    ]);
                }
            }
        }

        foreach ($nonHeaders as $name => $member) {
            // "headers" are not "header"
            if ('headers' !== $member->getLocation()) {
                continue;
            }
            unset($nonHeaders[$name]);

            $locationName = strtolower($member->getLocationName() ?? $member->getName());
            $body .= strtr('
                $this->NAME = [];
                foreach ($headers as $name => $value) {
                    if (substr($name, 0, LENGTH) === "LOCATION_NAME") {
                        $this->NAME[$name] = $value[0];
                    }
                }
            ', [
                'NAME' => $member->getName(),
                'LENGTH' => \strlen($locationName),
                'LOCATION_NAME' => $locationName,
            ]);
        }

        // Prepend with $headers = ...
        if (!empty($body)) {
            $body = '$headers = $response->getHeaders(false);' . "\n\n" . $body;
        }

        $body .= "\n";
        $xmlParser = '';
        if (null !== $payloadProperty = $shape->getPayload()) {
            $member = $shape->getMember($payloadProperty);
            if ($member->isStreaming()) {
                // Make sure we can stream this.
                $namespace->addUse(StreamableBody::class);
                $body .= strtr('$this->PROPERTY_NAME = new StreamableBody($httpClient->stream($response));', ['PROPERTY_NAME' => $payloadProperty]);
            } else {
                $xmlParser = $this->parseXml($shape);
            }
        } else {
            $xmlParser = $this->parseXml($shape);
        }

        if (!empty($xmlParser)) {
            $body .= '$data = new \SimpleXMLElement($response->getContent(false));';
            if (null !== $wrapper = $operation->getOutputResultWrapper()) {
                $body .= strtr('$data = $data->WRAPPER;' . "\n", ['WRAPPER' => $wrapper]);
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

    private function parseXml(StructureShape $shape): string
    {
        if (null === $this->xmlParser) {
            $this->xmlParser = new XmlParser();
        }

        return $this->xmlParser->parseXmlResponseRoot($shape);
    }
}
