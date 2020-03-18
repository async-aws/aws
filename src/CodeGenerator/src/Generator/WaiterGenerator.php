<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ErrorWaiterAcceptor;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Definition\Waiter;
use AsyncAws\CodeGenerator\Definition\WaiterAcceptor;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Waiter as WaiterResult;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
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
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var InputGenerator
     */
    private $inputGenerator;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    public function __construct(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Waiter $waiter): void
    {
        $operation = $waiter->getOperation();
        $inputShape = $operation->getInput();

        $namespace = ClassFactory::fromExistingClass($this->namespaceRegistry->getClient($operation->getService())->getFqdn());
        $namespace->addUse(HttpException::class);
        $namespace->addUse(RuntimeException::class);

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

        $this->generateMethod($namespace, $class, $waiter, $operation, $inputShape);

        $this->fileWriter->write($namespace);
    }

    private function generateMethod(PhpNamespace $namespace, ClassType $class, Waiter $waiter, Operation $operation, StructureShape $inputShape): void
    {
        $inputClass = $this->inputGenerator->generate($operation);
        $namespace->addUse($inputClass->getFqdn());

        $resultClass = $this->generateWaiterResult($waiter);
        $namespace->addUse($resultClass->getFqdn());

        $method = $class->addMethod(\lcfirst($waiter->getName()))
            ->setComment('Check status of operation ' . \lcfirst($operation->getName()))
            ->addComment('@see ' . \lcfirst($operation->getName()))
            ->addComment($this->typeGenerator->generateDocblock($inputShape, $inputClass))
            ->setReturnType($resultClass->getFqdn())
            ->setBody(strtr('
                $input = INPUT_CLASS::create($input);
                $response = $this->getResponse($input->request());

                return new RESULT_CLASS($response, $this, $input);
            ', [
                'INPUT_CLASS' => $inputClass->getName(),
                'RESULT_CLASS' => $resultClass->getName(),
            ]));

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }
    }

    private function generateWaiterResult(Waiter $waiter): ClassName
    {
        $className = $this->namespaceRegistry->getWaiter($waiter);

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());

        $class->addConstant('WAIT_TIMEOUT', (float) ($waiter->getMaxAttempts() * $waiter->getDelay()))
            ->setVisibility(ClassType::VISIBILITY_PROTECTED);
        $class->addConstant('WAIT_DELAY', (float) $waiter->getDelay())
            ->setVisibility(ClassType::VISIBILITY_PROTECTED);

        $namespace->addUse(WaiterResult::class);
        $namespace->addUse(Result::class);
        $namespace->addUse(ResponseInterface::class);
        $namespace->addUse(HttpException::class);

        $inputClass = $this->inputGenerator->generate($waiter->getOperation());
        $namespace->addUse($inputClass->getFqdn());
        $clientClass = $this->namespaceRegistry->getClient($waiter->getOperation()->getService());
        $namespace->addUse($clientClass->getFqdn());

        $class->addExtend(WaiterResult::class);

        $class->addMethod('refreshState')
            ->setReturnType(WaiterResult::class)
            ->setVisibility(ClassType::VISIBILITY_PROTECTED)
            ->setBody(strtr('
                if (!$this->awsClient instanceOf CLIENT_CLASSNAME) {
                    throw new \InvalidArgumentException(\'missing client injected in waiter result\');
                }
                if (!$this->input instanceOf INPUT_CLASSNAME) {
                    throw new \InvalidArgumentException(\'missing last request injected in waiter result\');
                }

                return $this->awsClient->WAITER_NAME($this->input);
            ', [
                'CLIENT_CLASSNAME' => $clientClass->getName(),
                'INPUT_CLASSNAME' => $inputClass->getName(),
                'WAITER_NAME' => $waiter->getName(),
            ]))
        ;

        $method = $class->addMethod('extractState')
            ->setReturnType('string')
            ->setProtected()
            ->setBody(strtr('
                ACCEPTOR_CODE

                return $exception === null ? self::STATE_PENDING :  self::STATE_FAILURE;
            ', ['ACCEPTOR_CODE' => \implode("\n", \array_map([$this, 'getAcceptorBody'], $waiter->getAcceptors()))]));
        $method->addParameter('response')->setType(Response::class);
        $method->addParameter('exception')->setType(HttpException::class)->setNullable(true);

        $this->fileWriter->write($namespace);

        return $className;
    }

    private function getAcceptorBody(WaiterAcceptor $acceptor): string
    {
        if ($acceptor instanceof ErrorWaiterAcceptor) {
            return $this->getAcceptorErrorBody($acceptor);
        }

        switch ($acceptor->getMatcher()) {
            case WaiterAcceptor::MATCHER_STATUS:
                return $this->getAcceptorStatusBody($acceptor);
            default:
                throw new \RuntimeException(sprintf('Acceptor matcher "%s" is not yet implemented', $acceptor->getMatcher()));
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

    private function getAcceptorErrorBody(ErrorWaiterAcceptor $acceptor): string
    {
        $checks = ['$exception !== null'];
        $error = $acceptor->getError();
        if (null !== $code = $error->getCode()) {
            $checks[] = '$exception->getAwsCode() === ' . \var_export($code, true);
        }
        if (null !== $code = $error->getStatusCode()) {
            $checks[] = '$exception->getCode() === ' . \var_export($code, true);
        }

        return strtr('
            if (EXPECTED) {
                return self::BEHAVIOR;
            }
        ', [
            'EXPECTED' => \implode('&&', $checks),
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
                throw new \RuntimeException(sprintf('Acceptor state "%s" is not yet implemented', $acceptor->getState()));
        }
    }
}
