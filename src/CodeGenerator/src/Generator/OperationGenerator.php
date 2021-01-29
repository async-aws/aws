<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use Nette\PhpGenerator\Method;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class OperationGenerator
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
     * @var PaginationGenerator
     */
    private $paginationGenerator;

    /**
     * @var TestGenerator
     */
    private $testGenerator;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    /**
     * @var ExceptionGenerator
     */
    private $exceptionGenerator;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator, PaginationGenerator $paginationGenerator, TestGenerator $testGenerator, ExceptionGenerator $exceptionGenerator, ?TypeGenerator $typeGenerator = null)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->resultGenerator = $resultGenerator;
        $this->paginationGenerator = $paginationGenerator;
        $this->testGenerator = $testGenerator;
        $this->exceptionGenerator = $exceptionGenerator;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Operation $operation): void
    {
        $inputShape = $operation->getInput();
        $inputClass = $this->inputGenerator->generate($operation);

        $className = $this->namespaceRegistry->getClient($operation->getService());
        $classBuilder = $this->classRegistry->register($className->getFqdn(), true);
        $classBuilder->addUse($inputClass->getFqdn());

        $method = $classBuilder->addMethod(\lcfirst(GeneratorHelper::normalizeName($operation->getMethodName())));
        if (null !== $documentation = $operation->getDocumentation()) {
            $method->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        if (null !== $documentation = $operation->getUserGuideDocumentationUrl()) {
            $method->addComment('@see ' . $documentation);
        }
        $method->addComment('@see ' . $operation->getApiReferenceDocumentationUrl());
        if (null !== $prefix = $operation->getService()->getEndpointPrefix()) {
            $method->addComment('@see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-' . $prefix . '-' . $operation->getService()->getApiVersion() . '.html#' . \strtolower($operation->getName()));
        }
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($inputShape, $inputClass, true, false, false, ['  @region?: string,']);
        $method->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }

        foreach ($operation->getErrors() as $error) {
            $errorClass = $this->namespaceRegistry->getException($error);
            $method->addComment('@throws ' . $errorClass->getName());
            $classBuilder->addUse($errorClass->getFqdn());
        }

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }
        if (null !== $operation->getOutput()) {
            $resultClass = $this->resultGenerator->generate($operation);
            if (null !== $operation->getPagination()) {
                $this->paginationGenerator->generate($operation);
            }

            $method->setReturnType($resultClass->getFqdn());
            $classBuilder->addUse($resultClass->getFqdn());
        } else {
            $resultClass = null;
            $method->setReturnType(Result::class);
            $classBuilder->addUse(Result::class);
        }

        $classBuilder->addUse(RequestContext::class);
        // Generate method body
        $this->setMethodBody($method, $operation, $inputClass, $resultClass, $classBuilder);

        $this->testGenerator->generate($operation);
    }

    private function setMethodBody(Method $method, Operation $operation, ClassName $inputClass, ?ClassName $resultClass, ClassBuilder $classBuilder): void
    {
        $body = '';
        if ($operation->isDeprecated()) {
            $method->addComment('@deprecated');
            $body .= '@trigger_error(\sprintf(\'The operation "%s" is deprecated by AWS.\', __FUNCTION__), E_USER_DEPRECATED);';
        }

        $body .= '
                $input = INPUT_CLASS::create($input);
                $response = $this->getResponse($input->request(), new RequestContext(["operation" => OPERATION_NAME, "region" => $input->getRegion()EXCEPTION_MAPPING]));
        ';
        if ((null !== $pagination = $operation->getPagination()) && !empty($pagination->getOutputToken())) {
            $body .= '
                return new RESULT_CLASS($response, $this, $input);
            ';
        } else {
            $body .= '
                return new RESULT_CLASS($response);
            ';
        }

        $mapping = [];
        foreach ($operation->getErrors() as $error) {
            $errorClass = $this->exceptionGenerator->generate($operation, $error);
            $classBuilder->addUse($errorClass->getFqdn());

            $mapping[] = sprintf('%s => %s::class,', var_export($error->getCode() ?? $error->getName(), true), $errorClass->getName());
        }

        $method->setBody(strtr($body, [
            'INPUT_CLASS' => $inputClass->getName(),
            'OPERATION_NAME' => \var_export($operation->getName(), true),
            'RESULT_CLASS' => $resultClass ? $resultClass->getName() : 'Result',
            'EXCEPTION_MAPPING' => $mapping ? ", 'exceptionMapping' => [\n" . implode("\n", $mapping) . "\n]" : '',
        ]));
    }
}
