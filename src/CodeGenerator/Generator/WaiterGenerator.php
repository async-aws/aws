<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Definition\Waiter;
use AsyncAws\CodeGenerator\Definition\WaiterAcceptor;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\RuntimeException;
use Nette\PhpGenerator\ClassType;

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
        if (null === $operation = $waiter->getOperation()) {
            throw new \InvalidArgumentException(sprintf('Waiter "%s" does not have operation', $waiter->getName()));
        }

        $inputShape = $operation->getInput();
        $this->generateInputClass($service, $operation, $baseNamespace . '\\Input', $inputShape, true);

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $namespace->addUse($baseNamespace . '\\Input\\' . GeneratorHelper::safeClassName($inputShape->getName()));
        $namespace->addUse(HttpException::class);
        $namespace->addUse(RuntimeException::class);

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

        $this->generateHaserMethod($class, $waiter, $operation, $inputShape, $baseNamespace);
        $this->generateWaiterMethod($class, $waiter, $operation, $inputShape, $baseNamespace);

        $this->fileWriter->write($namespace);
    }

    private function generateHaserMethod(ClassType $class, Waiter $waiter, Operation $operation, StructureShape $inputShape, string $baseNamespace)
    {
        $class->removeMethod($methodName = \lcfirst($waiter->getName()));
        $method = $class->addMethod($methodName);
        $method->addComment('@see ' . \lcfirst($operation->getName()));
        $method->addComment(GeneratorHelper::getParamDocblock($inputShape, $baseNamespace . '\\Input', GeneratorHelper::safeClassName($inputShape->getName())));

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }

        $method->setReturnType('bool');
        $method->setBody($this->getHaserMethodBody($waiter, $operation));
    }

    private function generateWaiterMethod(ClassType $class, Waiter $waiter, Operation $operation, StructureShape $inputShape, string $baseNamespace)
    {
        $class->removeMethod($methodName = 'waitFor' . $waiter->getName());
        $method = $class->addMethod($methodName);
        $method->addComment('@see ' . \lcfirst($waiter->getName()));
        $method->addComment(GeneratorHelper::getParamDocblock($inputShape, $baseNamespace . '\\Input', GeneratorHelper::safeClassName($inputShape->getName())));

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }
        $method->addParameter('delay')->setType('float')->setDefaultValue($waiter->getDelay());
        $method->addParameter('maxAttempts')->setType('int')->setDefaultValue($waiter->getMaxAttempts());

        $method->setReturnType('void');
        $method->setBody($this->getWaiterMethodBody($waiter, $operation));
    }

    private function getHaserMethodBody(Waiter $waiter, Operation $operation): string
    {
        return \strtr('
            $result = $this->OPERATION_NAME($input);
            $e = null;
            try {
                $result->resolve();
            } catch (HttpException $e) {
            }

            ACCEPTOR_CODE;

            return false;
        ', [
            'OPERATION_NAME' => $operation->getName(),
            'ACCEPTOR_CODE' => implode("\n", \array_map(function (WaiterAcceptor $acceptor) use ($waiter): string {
                return $this->getAcceptorBody($waiter, $acceptor, true);
            }, $waiter->getAcceptors())),
        ]);
    }

    private function getWaiterMethodBody(Waiter $waiter, Operation $operation): string
    {
        return \strtr('
            $attempts = 0;
            for(;;) {
                $result = $this->OPERATION_NAME($input);
                $e = null;
                try {
                    $result->resolve();
                } catch (HttpException $e) {
                }

                for(;;) {
                    ACCEPTOR_CODE;

                    break;
                }

                if (++$attempts >= $maxAttempts) {
                    throw new RuntimeException(\sprintf(\'Stopped waiting for "%s" after "%s" attempts.\', "OPERATION_NAME", $attempts));
                }
                \usleep((int) ceil($delay * 1000000));
            }
        ', [
            'OPERATION_NAME' => $operation->getName(),
            'ACCEPTOR_CODE' => implode("\n", \array_map(function (WaiterAcceptor $acceptor) use ($waiter): string {
                return $this->getAcceptorBody($waiter, $acceptor, false);
            }, $waiter->getAcceptors())),
        ]);
    }

    private function getAcceptorBody(Waiter $waiter, WaiterAcceptor $acceptor, bool $returnBool): string
    {
        switch ($acceptor->getMatcher()) {
            case WaiterAcceptor::MATCHER_STATUS:
                return $this->getAcceptorStatusBody($waiter, $acceptor, $returnBool);
            case WaiterAcceptor::MATCHER_ERROR:
                return $this->getAcceptorErrorBody($waiter, $acceptor, $returnBool);
            default:
                throw new \RuntimeException(sprintf('Acceptor matcher "%s" is not yet implemented', $acceptor->getMatcher()));
        }
    }

    private function getAcceptorStatusBody(Waiter $waiter, WaiterAcceptor $acceptor, bool $returnBool): string
    {
        return strtr('
            if (EXPECTED === ($result->info()["status"] ?? 0)) {
                BEHAVIOR
            }
        ', [
            'EXPECTED' => $acceptor->getExpected(),
            'BEHAVIOR' => $this->getAcceptorBehavior($waiter, $acceptor, $returnBool),
        ]);
    }

    private function getAcceptorErrorBody(Waiter $waiter, WaiterAcceptor $acceptor, bool $returnBool): string
    {
        return strtr('
            if ($e !== null && $e->getAwsCode() === EXPECTED) {
                BEHAVIOR
            }
        ', [
            'EXPECTED' => \var_export($acceptor->getExpected(), true),
            'BEHAVIOR' => $this->getAcceptorBehavior($waiter, $acceptor, $returnBool),
        ]);
    }

    private function getAcceptorBehavior(Waiter $waiter, WaiterAcceptor $acceptor, bool $returnBool): string
    {
        switch ($acceptor->getState()) {
            case WaiterAcceptor::STATE_SUCCESS:
                return $returnBool ? 'return true;' : 'return;';
            case WaiterAcceptor::STATE_FAILURE:
                return $returnBool ? 'return false;' : strtr('throw new RuntimeException(\sprintf(\'Failed waiting for "%s".\', "OPERATION_NAME"));', ['OPERATION_NAME' => $waiter->getName()]);
            case WaiterAcceptor::STATE_RETRY:
                return $returnBool ? 'return false;' : 'break;';
            default:
                throw new \RuntimeException(sprintf('Acceptor state "%s" is not yet implemented', $acceptor->getState()));
        }
    }
}
