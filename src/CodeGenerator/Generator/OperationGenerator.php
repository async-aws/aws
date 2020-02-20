<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
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
    public function generate(Operation $operation, string $service, string $baseNamespace): void
    {
        $inputShape = $operation->getInput();
        $this->generateInputClass($service, $operation, $baseNamespace . '\\Input', $inputShape, true);

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $safeClassName = GeneratorHelper::safeClassName($inputShape->getName());
        $namespace->addUse($baseNamespace . '\\Input\\' . $safeClassName);
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
        $method->addComment(GeneratorHelper::getParamDocblock($inputShape, $baseNamespace . '\\Input', $safeClassName));

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }

        if (null !== $output = $operation->getOutput()) {
            $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, GeneratorHelper::safeClassName($output->getName()));
            $method->setReturnType($outputClass);
            $namespace->addUse($outputClass);
        } else {
            $method->setReturnType(Result::class);
            $namespace->addUse(Result::class);
        }

        // Generate method body
        $this->setMethodBody($inputShape, $method, $operation, $inputShape->getName());

        $this->fileWriter->write($namespace);
    }

    private function setMethodBody(StructureShape $inputShape, Method $method, Operation $operation, $inputClassName): void
    {
        $params = ['$response', '$this->httpClient'];
        if ((null !== $pagination = $operation->getPagination()) && !empty($pagination->getOutputToken())) {
            $params = \array_merge($params, ['$this', '$input']);
        }

        if (null !== $outputShape = $operation->getOutput()) {
            $safeOutputClassName = GeneratorHelper::safeClassName($outputShape->getName());
        } else {
            $safeOutputClassName = 'Result';
        }

        $method->setBody(strtr('
$input = SAFE_CLASS::create($input);
$input->validate();

$response = $this->getResponse(
    METHOD,
    $input->requestBody(),
    $input->requestHeaders(),
    $this->getEndpoint($input->requestUri(), $input->requestQuery())
);

return new RESULT_CLASS(RESULT_PARAM);
        ', [
            'SAFE_CLASS' => GeneratorHelper::safeClassName($inputClassName),
            'METHOD' => \var_export($operation->getHttpMethod(), true),
            'RESULT_CLASS' => $safeOutputClassName,
            'RESULT_PARAM' => \implode(', ', $params),
        ]));
    }
}
