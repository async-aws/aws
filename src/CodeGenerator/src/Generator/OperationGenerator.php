<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
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
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    public function __construct(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator, PaginationGenerator $paginationGenerator, TestGenerator $testGenerator, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->resultGenerator = $resultGenerator;
        $this->paginationGenerator = $paginationGenerator;
        $this->testGenerator = $testGenerator;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Operation $operation): void
    {
        $inputShape = $operation->getInput();
        $inputClass = $this->inputGenerator->generate($operation);

        $namespace = ClassFactory::fromExistingClass($this->namespaceRegistry->getClient($operation->getService())->getFqdn());
        $namespace->addUse($inputClass->getFqdn());
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

        $method = $class->addMethod(\lcfirst($operation->getName()));
        if (null !== $documentation = $operation->getDocumentation()) {
            $method->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        if (null !== $documentationUrl = $operation->getDocumentationUrl()) {
            $method->addComment('@see ' . $documentationUrl);
        } elseif (null !== $prefix = $operation->getService()->getEndpointPrefix()) {
            $method->addComment('@see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-' . $prefix . '-' . $operation->getService()->getApiVersion() . '.html#' . \strtolower($operation->getName()));
        }
        $method->addComment($this->typeGenerator->generateDocblock($inputShape, $inputClass));

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
            $namespace->addUse($resultClass->getFqdn());
        } else {
            $resultClass = null;
            $method->setReturnType(Result::class);
            $namespace->addUse(Result::class);
        }

        $namespace->addUse(RequestContext::class);
        // Generate method body
        $this->setMethodBody($method, $operation, $inputClass, $resultClass);

        $this->fileWriter->write($namespace);

        $this->testGenerator->generate($operation);
    }

    private function setMethodBody(Method $method, Operation $operation, ClassName $inputClass, ?ClassName $resultClass): void
    {
        if ((null !== $pagination = $operation->getPagination()) && !empty($pagination->getOutputToken())) {
            $body = '
                $input = INPUT_CLASS::create($input);
                $response = $this->getResponse($input->request(), new RequestContext(["operation" => OPERATION_NAME]));

                return new RESULT_CLASS($response, $this, $input);
            ';
        } else {
            $body = '
                $response = $this->getResponse(INPUT_CLASS::create($input)->request(), new RequestContext(["operation" => OPERATION_NAME]));

                return new RESULT_CLASS($response);
            ';
        }

        $method->setBody(strtr($body, [
            'INPUT_CLASS' => $inputClass->getName(),
            'OPERATION_NAME' => \var_export($operation->getName(), true),
            'RESULT_CLASS' => $resultClass ? $resultClass->getName() : 'Result',
        ]));
    }
}
