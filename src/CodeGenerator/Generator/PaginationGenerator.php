<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Pagination;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
use AsyncAws\Core\Exception\LogicException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate API pagination methods.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class PaginationGenerator
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var InputGenerator
     */
    private $inputGenerator;

    /**
     * @var ResultGenerator
     */
    private $resultGenerator;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var TypeGenerator|null
     */
    private $typeGenerator;

    public function __construct(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->resultGenerator = $resultGenerator;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generate(Operation $operation): void
    {
        if (null === $pagination = $operation->getPagination()) {
            throw new LogicException(sprintf('The operation "%s" does not have any pagination to generate', $operation->getName()));
        }
        if (null === $output = $operation->getOutput()) {
            throw new LogicException(sprintf('The operation "%s" does not have output for the pagination', $operation->getName()));
        }

        $this->generateOutputPagination($operation, $pagination, $output);
    }

    private function generateOutputPagination(Operation $operation, Pagination $pagination, StructureShape $shape): void
    {
        $outputClass = $this->resultGenerator->generate($operation);

        $namespace = ClassFactory::fromExistingClass($outputClass->getFqdn());
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

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
            [$returnType, $iteratorType] = $this->typeGenerator->getPhpType($listShape, true);
            $iteratorTypes[] = $iteratorType;

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
                            $pagination, $namespace, $operation
                        ),
                    ]));
            }

            $method
                ->addComment("@return iterable<$iteratorType>");
        }

        $iteratorType = implode('|', $iteratorTypes);

        $class->addMethod('getIterator')
            ->setReturnType(\Traversable::class)
            ->addComment('Iterates over ' . implode(' then ', $pagination->getResultkey()))
            ->addComment("@return \Traversable<$iteratorType>")
            ->setBody($this->generateOutputPaginationLoader($iteratorBody, $pagination, $namespace, $operation))
        ;
        if (!empty($pagination->getOutputToken())) {
            $class->addProperty('prefetch')->setVisibility(ClassType::VISIBILITY_PRIVATE)->setComment('@var self[]')->setValue([]);
            $destruct = $class->getMethod('__destruct');
            $destruct->setBody('
                    while (!empty($this->prefetch)) {
                        array_shift($this->prefetch)->cancel();
                    }

                ' . $destruct->getBody());
        }

        $this->fileWriter->write($namespace);
    }

    private function generateOutputPaginationLoader(string $iterator, Pagination $pagination, PhpNamespace $namespace, Operation $operation): string
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

        $inputClass = $this->inputGenerator->generate($operation);
        $namespace->addUse($inputClass->getFqdn());
        $clientClass = $this->namespaceRegistry->getClient($operation->getService());
        $namespace->addUse($clientClass->getFqdn());

        return strtr('
            if (!$this->awsClient instanceOf CLIENT_CLASSNAME) {
                throw new \InvalidArgumentException(\'missing client injected in paginated result\');
            }
            if (!$this->input instanceOf INPUT_CLASSNAME) {
                throw new \InvalidArgumentException(\'missing last request injected in paginated result\');
            }
            $input = clone $this->input;
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
            'CLIENT_CLASSNAME' => $clientClass->getName(),
            'INPUT_CLASSNAME' => $inputClass->getName(),
            'ITERATE_PROPERTIES_CODE' => $iterator,
            'MORE_CONDITION' => $moreCondition,
            'SET_TOKEN_CODE' => $setter,
            'OPERATION_NAME' => $operation->getName(),
        ]);
    }
}
