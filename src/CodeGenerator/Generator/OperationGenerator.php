<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Result;
use AsyncAws\Core\XmlBuilder;
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

        $class->removeMethod(\lcfirst($operation->getName()));
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
            $namespace->addUse(XmlBuilder::class);
        } else {
            $method->setReturnType(Result::class);
            $namespace->addUse(Result::class);
        }

        // Generate method body
        $this->setMethodBody($inputShape, $method, $operation, $inputShape->getName());

        $this->fileWriter->write($namespace);
    }

    /**
     * Pick only the config from $shapes we are interested in.
     */
    private function buildXmlConfig(Shape $shape): array
    {
        $xml[$shape->getName()] = [
            'type' => $shape->getType(),
        ];
        if ($shape instanceof StructureShape) {
            $members = [];
            foreach ($shape->getMembers() as $member) {
                $memberShape = $member->getShape();
                $members[$member->getName()] = ['shape' => $memberShape->getName()];
                if (null !== $locationName = $member->getLocationName()) {
                    $members[$member->getName()] += ['locationName' => $locationName];
                }
                $xml += $this->buildXmlConfig($memberShape);
            }

            $xml[$shape->getName()]['members'] = $members;
        } elseif ($shape instanceof ListShape) {
            $memberShape = $shape->getMember()->getShape();
            $xml[$shape->getName()]['member'] = ['shape' => $memberShape->getName()];

            $xml += $this->buildXmlConfig($memberShape);
        }

        return $xml;
    }

    private function setMethodBody(StructureShape $inputShape, Method $method, Operation $operation, $inputClassName): void
    {
        $safeInputClassName = GeneratorHelper::safeClassName($inputClassName);
        $body = strtr('
            $input = SAFE_CLASS::create($input);
            $input->validate();
        ', ['SAFE_CLASS' => $safeInputClassName]);

        if (null !== $payloadProperty = $inputShape->getPayload()) {
            $member = $inputShape->getMember($payloadProperty);
            if ($member->isStreaming()) {
                $body .= '$payload = $input->get' . $payloadProperty . '() ?? "";';
            } else {
                // Build XML
                $memberShape = $member->getShape();
                $xml = $this->buildXmlConfig($memberShape);
                $xml['_root'] = [
                    'type' => $memberShape->getName(),
                    'xmlName' => $member->getLocationName(),
                    'uri' => $member->getXmlNamespaceUri(),
                ];

                $body .= '$xmlConfig = ' . GeneratorHelper::printArray($xml) . ";\n";
                $body .= '$payload = (new XmlBuilder($input->requestBody(), $xmlConfig))->getXml();' . "\n";
            }
            $payloadVariable = '$payload';
        } else {
            // This is a normal body application/x-www-form-urlencoded
            $payloadVariable = '$input->requestBody()';
        }

        $params = ['$response', '$this->httpClient'];
        if ((null !== $pagination = $operation->getPagination()) && !empty($pagination->getOutputToken())) {
            $params = \array_merge($params, ['$this', '$input']);
        }
        $param = \implode(', ', $params);

        if (null !== $outputShape = $operation->getOutput()) {
            $safeOutputClassName = GeneratorHelper::safeClassName($outputShape->getName());
            $return = "return new {$safeOutputClassName}($param);";
        } else {
            $return = "return new Result($param);";
        }

        $method->setBody(
            $body .
            <<<PHP

\$response = \$this->getResponse(
    '{$operation->getHttpMethod()}',
    $payloadVariable,
    \$input->requestHeaders(),
    \$this->getEndpoint(\$input->requestUri(), \$input->requestQuery())
);

$return
PHP
        );
    }
}
