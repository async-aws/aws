<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ErrorWaiterAcceptor;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Definition\Waiter;
use AsyncAws\CodeGenerator\Definition\WaiterAcceptor;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\CodeGenerator\Generator\ResponseParser\ParserProvider;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Waiter as WaiterResult;
use Nette\PhpGenerator\Visibility;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Generate API client waiters methods.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class WaiterGenerator
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
     * @var TypeGenerator
     */
    private $typeGenerator;

    /**
     * @var ExceptionGenerator
     */
    private $exceptionGenerator;

    /**
     * @var ParserProvider
     */
    private $parserProvider;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ExceptionGenerator $exceptionGenerator, ParserProvider $parserProvider, ?TypeGenerator $typeGenerator = null)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->exceptionGenerator = $exceptionGenerator;
        $this->parserProvider = $parserProvider;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Waiter $waiter): void
    {
        $operation = $waiter->getOperation();
        $inputShape = $operation->getInput();

        $className = $this->namespaceRegistry->getClient($operation->getService());
        $classBuilder = $this->classRegistry->register($className->getFqdn(), true);
        $classBuilder->addUse(HttpException::class);
        $classBuilder->addUse(RuntimeException::class);
        $classBuilder->addUse(RequestContext::class);

        $this->generateMethod($classBuilder, $waiter, $operation, $inputShape);
    }

    private function generateMethod(ClassBuilder $classBuilder, Waiter $waiter, Operation $operation, StructureShape $inputShape): void
    {
        $inputClass = $this->inputGenerator->generate($operation);
        $classBuilder->addUse($inputClass->getFqdn());

        $resultClass = $this->generateWaiterResult($waiter);
        $classBuilder->addUse($resultClass->getFqdn());

        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($inputShape, $inputClass, true, false, false, ['  \'@region\'?: string|null,']);

        $mapping = [];
        foreach ($operation->getErrors() as $error) {
            $errorClass = $this->exceptionGenerator->generate($operation, $error);
            $classBuilder->addUse($errorClass->getFqdn());

            $mapping[] = \sprintf('%s => %s::class,', var_export($error->getCode() ?? $error->getName(), true), $errorClass->getName());
        }

        $method = $classBuilder->addMethod(lcfirst(GeneratorHelper::normalizeName($waiter->getName())))
            ->setComment('Check status of operation ' . lcfirst(GeneratorHelper::normalizeName($operation->getName())))
            ->setComment('')
            ->addComment('@see ' . lcfirst(GeneratorHelper::normalizeName($operation->getName())))
            ->addComment($doc)
            ->setReturnType($resultClass->getFqdn())
            ->setBody(strtr('
                $input = INPUT_CLASS::create($input);
                $response = $this->getResponse($input->request(), new RequestContext(["operation" => OPERATION_NAME, "region" => $input->getRegion()EXCEPTION_MAPPING]));

                return new RESULT_CLASS($response, $this, $input);
            ', [
                'INPUT_CLASS' => $inputClass->getName(),
                'OPERATION_NAME' => var_export($operation->getName(), true),
                'RESULT_CLASS' => $resultClass->getName(),
                'EXCEPTION_MAPPING' => $mapping ? ", 'exceptionMapping' => [\n" . implode("\n", $mapping) . "\n]" : '',
            ]));
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }
    }

    private function generateWaiterResult(Waiter $waiter): ClassName
    {
        $className = $this->namespaceRegistry->getWaiter($waiter);

        $classBuilder = $this->classRegistry->register($className->getFqdn());

        $classBuilder->addConstant('WAIT_TIMEOUT', (float) ($waiter->getMaxAttempts() * $waiter->getDelay()))
            ->setVisibility(Visibility::Protected);
        $classBuilder->addConstant('WAIT_DELAY', (float) $waiter->getDelay())
            ->setVisibility(Visibility::Protected);

        $classBuilder->addUse(WaiterResult::class);
        $classBuilder->addUse(Result::class);
        $classBuilder->addUse(ResponseInterface::class);
        $classBuilder->addUse(HttpException::class);

        $inputClass = $this->inputGenerator->generate($waiter->getOperation());
        $classBuilder->addUse($inputClass->getFqdn());
        $clientClass = $this->namespaceRegistry->getClient($waiter->getOperation()->getService());
        $classBuilder->addUse($clientClass->getFqdn());
        $classBuilder->addUse(InvalidArgument::class);

        $classBuilder->setExtends(WaiterResult::class);

        $classBuilder->addMethod('refreshState')
            ->setReturnType(WaiterResult::class)
            ->setVisibility(Visibility::Protected)
            ->setBody(strtr('
                if (!$this->awsClient instanceOf CLIENT_CLASSNAME) {
                    throw new InvalidArgument(\'missing client injected in waiter result\');
                }
                if (!$this->input instanceOf INPUT_CLASSNAME) {
                    throw new InvalidArgument(\'missing last request injected in waiter result\');
                }

                return $this->awsClient->WAITER_NAME($this->input);
            ', [
                'CLIENT_CLASSNAME' => $clientClass->getName(),
                'INPUT_CLASSNAME' => $inputClass->getName(),
                'WAITER_NAME' => GeneratorHelper::normalizeName($waiter->getName()),
            ]))
        ;

        $method = $classBuilder->addMethod('extractState')
            ->setReturnType('string')
            ->setProtected()
            ->setBody(strtr('
                ACCEPTOR_CODE

                return $exception === null ? self::STATE_PENDING :  self::STATE_FAILURE;
            ', ['ACCEPTOR_CODE' => implode("\n", array_map(function ($acceptor) use ($waiter, $classBuilder) {
                return $this->getAcceptorBody($waiter, $acceptor, $classBuilder);
            }, $waiter->getAcceptors()))]));
        $method->addParameter('response')->setType(Response::class);
        $method->addParameter('exception')->setType(HttpException::class)->setNullable(true);

        $classBuilder->addUse(Response::class);

        return $className;
    }

    private function getAcceptorBody(Waiter $waiter, WaiterAcceptor $acceptor, ClassBuilder $classBuilder): string
    {
        if ($acceptor instanceof ErrorWaiterAcceptor) {
            return $this->getAcceptorErrorBody($acceptor);
        }

        switch ($acceptor->getMatcher()) {
            case WaiterAcceptor::MATCHER_STATUS:
                return $this->getAcceptorStatusBody($acceptor);
            case WaiterAcceptor::MATCHER_PATH:
                return $this->getAcceptorPathBody($waiter, $acceptor, $classBuilder);
            default:
                throw new \RuntimeException(\sprintf('Acceptor matcher "%s" is not yet implemented', $acceptor->getMatcher()));
        }
    }

    private function getAcceptorStatusBody(WaiterAcceptor $acceptor): string
    {
        return strtr('
            if (EXPECTED === $response->getStatusCode()) {
                return self::BEHAVIOR;
            }
        ', [
            'EXPECTED' => $acceptor->getExpected(),
            'BEHAVIOR' => $this->getAcceptorBehavior($acceptor),
        ]);
    }

    private function getAcceptorPathBody(Waiter $waiter, WaiterAcceptor $acceptor, ClassBuilder $classBuilder): string
    {
        $operation = $waiter->getOperation();
        $parserResult = $this->parserProvider->get($operation->getService())->generateForPath($operation->getOutput(), $acceptor->getArgument(), '$a');
        foreach ($parserResult->getUsedClasses() as $className) {
            $classBuilder->addUse($className->getFqdn());
        }
        $classBuilder->addMethods($parserResult->getExtraMethods());

        return strtr('
            if (200 === $response->getStatusCode()) {
                ACCESS;
                if (EXPECTED === $a) {
                    return self::BEHAVIOR;
                }
            }
        ', [
            'ACCESS' => $parserResult->getBody(),
            'EXPECTED' => var_export($acceptor->getExpected(), true),
            'BEHAVIOR' => $this->getAcceptorBehavior($acceptor),
        ]);
    }

    private function getAcceptorErrorBody(ErrorWaiterAcceptor $acceptor): string
    {
        $checks = [];
        $error = $acceptor->getError();
        if ($error->hasError()) {
            if (null !== $code = $error->getCode()) {
                $checks[] = var_export($code, true) . ' === $exception->getAwsCode()';
            }
            if (null !== $code = $error->getStatusCode()) {
                $checks[] = var_export($code, true) . ' === $exception->getCode()';
            }
        } elseif (null !== $expected = $acceptor->getExpected()) {
            $checks[] = var_export($expected, true) . ' === $exception->getAwsCode()';
        }

        if (0 === \count($checks)) {
            return '';
        }

        return strtr('
            if ($exception !== null && EXPECTED) {
                return self::BEHAVIOR;
            }
        ', [
            'EXPECTED' => implode('&&', $checks),
            'BEHAVIOR' => $this->getAcceptorBehavior($acceptor),
        ]);
    }

    private function getAcceptorBehavior(WaiterAcceptor $acceptor): string
    {
        switch ($acceptor->getState()) {
            case WaiterAcceptor::STATE_SUCCESS:
                return 'STATE_SUCCESS';
            case WaiterAcceptor::STATE_FAILURE:
                return 'STATE_FAILURE';
            case WaiterAcceptor::STATE_RETRY:
                return 'STATE_PENDING';
            default:
                throw new \RuntimeException(\sprintf('Acceptor state "%s" is not yet implemented', $acceptor->getState()));
        }
    }
}
