<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ErrorWaiterAcceptor;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Definition\Waiter;
use AsyncAws\CodeGenerator\Definition\WaiterAcceptor;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\RuntimeException;
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
    use MethodGeneratorTrait;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    public function __construct(FileWriter $fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Waiter $waiter, string $service, string $baseNamespace): void
    {
        $operation = $waiter->getOperation();
        $inputShape = $operation->getInput();
        $this->generateInputClass($service, $operation, $baseNamespace . '\\Input', $inputShape, true);

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $namespace->addUse($baseNamespace . '\\Input\\' . GeneratorHelper::safeClassName($inputShape->getName()));
        $namespace->addUse(HttpException::class);
        $namespace->addUse(RuntimeException::class);

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

        $this->generateMethod($namespace, $class, $waiter, $operation, $inputShape, $baseNamespace, $service);

        $this->fileWriter->write($namespace);
    }

    private function generateMethod(PhpNamespace $namespace, ClassType $class, Waiter $waiter, Operation $operation, StructureShape $inputShape, string $baseNamespace, string $service)
    {
        $namespace->addUse($inputFqdn = $baseNamespace . '\\Input\\' . ($inputClassName = GeneratorHelper::safeClassName($inputShape->getName())));
        $namespace->addUse($resultFqdn = $baseNamespace . '\\Result\\' . ($resultClassName = GeneratorHelper::safeClassName($waiter->getName() . 'Waiter')));

        $method = $class->addMethod(\lcfirst($waiter->getName()))
            ->setComment('Check status of operation ' . \lcfirst($operation->getName()))
            ->addComment('@see ' . \lcfirst($operation->getName()))
            ->addComment(GeneratorHelper::getParamDocblock($inputShape, $baseNamespace . '\\Input', $inputClassName))
            ->setReturnType($resultFqdn)
            ->setBody(strtr('
$input = SAFE_CLASS::create($input);
$input->validate();

$response = $this->getResponse(
    METHOD,
    $input->requestBody(),
    $input->requestHeaders(),
    $this->getEndpoint($input->requestUri(), $input->requestQuery())
);

return new RESULT_CLASS($response, $this->httpClient, $this, $input);
            ', [
                'SAFE_CLASS' => $inputClassName,
                'METHOD' => \var_export($operation->getHttpMethod(), true),
                'RESULT_CLASS' => $resultClassName,
            ]));

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }

        $this->generateWaiterResult($waiter, $baseNamespace, $service);
    }

    private function generateWaiterResult(Waiter $waiter, string $baseNamespace, string $service): void
    {
        $namespace = new PhpNamespace($baseNamespace . '\\Result');
        $className = GeneratorHelper::safeClassName($waiter->getName() . 'Waiter');
        $class = $namespace->addClass($className);

        $class->addConstant('WAIT_TIMEOUT', (float) ($waiter->getMaxAttempts() * $waiter->getDelay()))
            ->setVisibility(ClassType::VISIBILITY_PROTECTED);
        $class->addConstant('WAIT_DELAY', (float) $waiter->getDelay())
            ->setVisibility(ClassType::VISIBILITY_PROTECTED);

        $namespace->addUse(WaiterResult::class);
        $namespace->addUse(Result::class);
        $namespace->addUse(ResponseInterface::class);
        $namespace->addUse(HttpException::class);

        $inputSafeClassName = GeneratorHelper::safeClassName($waiter->getOperation()->getInput()->getName());
        $namespace->addUse($baseNamespace . '\\Input\\' . $inputSafeClassName);

        $clientClassName = $service . 'Client';
        $namespace->addUse($baseNamespace . '\\' . $clientClassName);

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
                'CLIENT_CLASSNAME' => $clientClassName,
                'INPUT_CLASSNAME' => $inputSafeClassName,
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
        $method->addParameter('response')->setType(ResponseInterface::class);
        $method->addParameter('exception')->setType(HttpException::class)->setNullable(true);

        $this->fileWriter->write($namespace);
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
