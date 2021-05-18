<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Pagination;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Exception\LogicException;
use Nette\PhpGenerator\Parameter;

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
     * @var ClassRegistry
     */
    private $classRegistry;

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
     * @var TypeGenerator|null
     */
    private $typeGenerator;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator, ?TypeGenerator $typeGenerator = null)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->resultGenerator = $resultGenerator;
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

        $classBuilder = $this->classRegistry->register($outputClass->getFqdn(), true);
        $classBuilder->addImplement(\IteratorAggregate::class);

        [$common, $moreResult, $outputToken, $resultKeys] = $this->extractPageProperties($operation, $pagination);

        $iteratorBody = '';
        $iteratorTypes = [];

        foreach ($resultKeys as $resultKey) {
            $initialGetter = null;
            if ($common || false !== strpos($resultKey, '.')) {
                $singlePage = true;
                $iteratorBody .= strtr('yield from PROPERTY_ACCESSOR;
                ', [
                    'PROPERTY_ACCESSOR' => $this->generateGetter('$page', $resultKey),
                ]);
            } else {
                $singlePage = empty($outputToken);
                $iteratorBody .= strtr('yield from $page->PROPERTY_ACCESSOR(SINGLE_PAGE_FLAG);
                ', [
                    'PROPERTY_ACCESSOR' => $initialGetter = 'get' . ucfirst(GeneratorHelper::normalizeName($resultKey)),
                    'SINGLE_PAGE_FLAG' => $singlePage ? '' : 'true',
                ]);

                if (!$classBuilder->hasMethod($initialGetter)) {
                    throw new \RuntimeException(sprintf('Unable to find the method "%s" in "%s"', $initialGetter, $shape->getName()));
                }
            }

            $resultShape = $shape;
            foreach (explode('.', $common) as $part) {
                if (!$part) {
                    continue;
                }
                $resultShape = $resultShape->getMember($part)->getShape();
            }
            foreach (explode('.', $resultKey) as $part) {
                if (!$part) {
                    continue;
                }
                $resultShape = $resultShape->getMember($part)->getShape();
            }

            if (!$resultShape instanceof ListShape) {
                throw new \RuntimeException('Cannot generate a pagination for a non-iterable result');
            }

            $listShape = $resultShape->getMember()->getShape();
            [$returnType, $iteratorType, $memberClassNames] = $this->typeGenerator->getPhpType($listShape);
            foreach ($memberClassNames as $memberClassName) {
                $classBuilder->addUse($memberClassName->getFqdn());
            }

            $iteratorTypes[] = $iteratorType;
            if (!$initialGetter) {
                continue;
            }

            $method = $classBuilder->getMethod($initialGetter)
                ->setReturnType('iterable')
                ->setComment('')
            ;
            if ($singlePage) {
                $method
                    ->setBody(strtr('
                        $this->initialize();
                        return $this->PROPERTY_NAME;
                    ', [
                        'PROPERTY_NAME' => GeneratorHelper::normalizeName($resultKey),
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
                        'PROPERTY_NAME' => GeneratorHelper::normalizeName($resultKey),
                        'PAGE_LOADER_CODE' => $this->generateOutputPaginationLoader(
                            strtr('yield from $page->PROPERTY_ACCESSOR(true);', ['PROPERTY_ACCESSOR' => 'get' . ucfirst(GeneratorHelper::normalizeName($resultKey))]),
                            $common, $moreResult, $outputToken, $pagination, $classBuilder, $operation
                        ),
                    ]));
            }

            $method
                ->addComment("@return iterable<$iteratorType>");
        }

        $iteratorType = implode('|', $iteratorTypes);

        if (!$common && 1 === \count($resultKeys) && false === strpos($resultKeys[0], '.')) {
            $body = strtr('yield from $this->PROPERTY_ACCESSOR();
            ', [
                'PROPERTY_ACCESSOR' => 'get' . ucfirst(GeneratorHelper::normalizeName($resultKeys[0])),
            ]);
        } else {
            $body = $this->generateOutputPaginationLoader($iteratorBody, $common, $moreResult, $outputToken, $pagination, $classBuilder, $operation);
        }

        $classBuilder->addMethod('getIterator')
            ->setReturnType(\Traversable::class)
            ->addComment('Iterates over ' . implode(' and ', $resultKeys))
            ->addComment("@return \Traversable<$iteratorType>")
            ->setBody($body)
        ;
        $classBuilder->addComment("@implements \IteratorAggregate<$iteratorType>");
    }

    private function generateOutputPaginationLoader(string $iterator, string $common, string $moreResult, array $outputToken, Pagination $pagination, ClassBuilder $classBuilder, Operation $operation): string
    {
        if (empty($outputToken)) {
            return strtr($iterator, ['$page->' => '$this->']);
        }

        $inputToken = $pagination->getInputToken();
        if (!$moreResult) {
            $moreCondition = '';
            foreach ($outputToken as $property) {
                $moreCondition .= strtr('$page->MORE_ACCESSOR()', [
                    'MORE_ACCESSOR' => 'get' . ucfirst(GeneratorHelper::normalizeName($property)),
                ]);
            }
        } else {
            $moreCondition = $this->generateGetter('$page', $moreResult);
        }
        $setter = '';
        foreach ($inputToken as $index => $property) {
            $setter .= strtr('
                $input->SETTER(GETTER);
            ', [
                'SETTER' => 'set' . ucfirst(GeneratorHelper::normalizeName($property)),
                'GETTER' => $this->generateGetter('$page', $outputToken[$index]),
            ]);
        }

        $inputClass = $this->inputGenerator->generate($operation);
        $classBuilder->addUse($inputClass->getFqdn());
        $clientClass = $this->namespaceRegistry->getClient($operation->getService());
        $classBuilder->addUse($clientClass->getFqdn());
        $classBuilder->addUse(InvalidArgument::class);

        return strtr('
            $client = $this->awsClient;
            if (!$client instanceOf CLIENT_CLASSNAME) {
                throw new InvalidArgument(\'missing client injected in paginated result\');
            }
            if (!$this->input instanceOf INPUT_CLASSNAME) {
                throw new InvalidArgument(\'missing last request injected in paginated result\');
            }
            $input = clone $this->input;
            $page = PAGE_INITIALIZER;
            while (true) {
                if (MORE_CONDITION) {
                    SET_TOKEN_CODE
                    $this->registerPrefetch($nextPage = $client->OPERATION_NAME($input));
                } else {
                    $nextPage = null;
                }

                ITERATE_PROPERTIES_CODE

                if ($nextPage === null) {
                    break;
                }

                $this->unregisterPrefetch($nextPage);
                $page = PAGE_NEXT;
            }
        ', [
            'PAGE_INITIALIZER' => $common ? $this->generateGetter('$this', $common) : '$this',
            'PAGE_NEXT' => $common ? $this->generateGetter('$nextPage', $common) : '$nextPage',
            'CLIENT_CLASSNAME' => $clientClass->getName(),
            'INPUT_CLASSNAME' => $inputClass->getName(),
            'ITERATE_PROPERTIES_CODE' => $iterator,
            'MORE_CONDITION' => $moreCondition,
            'SET_TOKEN_CODE' => $setter,
            'OPERATION_NAME' => GeneratorHelper::normalizeName($operation->getName()),
        ]);
    }

    private function generateGetter(string $property, string $expression): string
    {
        $getter = $property;
        foreach (explode('.', $expression) as $part) {
            $last = false;
            if ('[-1]' === substr($part, -4)) {
                $part = substr($part, 0, -4);
                $last = true;
            }
            if (!preg_match('/^[a-z]++$/i', $part)) {
                throw new LogicException(sprintf('The part "%s" of the getter expression "%s" is n9ot yet supported', $part, $expression));
            }
            $getter .= strtr('->getMETHOD()', ['METHOD' => ucfirst(GeneratorHelper::normalizeName($part))]);
            if ($last) {
                $getter = strtr('array_slice(GETTER, -1)[0]', ['GETTER' => $getter]);
            }
        }

        return $getter;
    }

    private function extractPageProperties(Operation $operation, Pagination $pagination): array
    {
        $more = $pagination->getMoreResults() ?? '';
        $output = array_map(function ($item) {
            return trim(explode('||', $item)[0]);
        }, $pagination->getOutputToken());
        $result = $pagination->getResultkey();

        if (empty($result)) {
            foreach ($operation->getOutput()->getMembers() as $member) {
                if ($member->getShape() instanceof ListShape) {
                    $result[] = $member->getName();
                }
            }
        }

        if (empty($result)) {
            throw new \RuntimeException('Pagination without resultkey is not yet implemented for ' . $operation->getName());
        }

        $items = array_merge(
            $output,
            $result
        );

        $common = $more;
        foreach ($items as $item) {
            while ('' !== $common && false === strrpos($item, $common)) {
                if (false === $pos = strrpos($common, '.')) {
                    $common = '';
                } else {
                    $common = substr($common, 0, $pos);
                }
            }
        }

        if ('' !== $common) {
            $more = substr($more, \strlen($common) + 1);
            $output = array_map(function ($item) use ($common) {
                return substr($item, \strlen($common) + 1);
            }, $output);
            $result = array_map(function ($item) use ($common) {
                return substr($item, \strlen($common) + 1);
            }, $result);
        }

        return [$common, $more, $output, $result];
    }
}
