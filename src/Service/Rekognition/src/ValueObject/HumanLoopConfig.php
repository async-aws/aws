<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Sets up the flow definition the image will be sent to if one of the conditions is met. You can also set certain
 * attributes of the image before review.
 */
final class HumanLoopConfig
{
    /**
     * The name of the human review used for this image. This should be kept unique within a region.
     *
     * @var string
     */
    private $humanLoopName;

    /**
     * The Amazon Resource Name (ARN) of the flow definition. You can create a flow definition by using the Amazon Sagemaker
     * CreateFlowDefinition [^1] Operation.
     *
     * [^1]: https://docs.aws.amazon.com/sagemaker/latest/dg/API_CreateFlowDefinition.html
     *
     * @var string
     */
    private $flowDefinitionArn;

    /**
     * Sets attributes of the input data.
     *
     * @var HumanLoopDataAttributes|null
     */
    private $dataAttributes;

    /**
     * @param array{
     *   HumanLoopName: string,
     *   FlowDefinitionArn: string,
     *   DataAttributes?: HumanLoopDataAttributes|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->humanLoopName = $input['HumanLoopName'] ?? $this->throwException(new InvalidArgument('Missing required field "HumanLoopName".'));
        $this->flowDefinitionArn = $input['FlowDefinitionArn'] ?? $this->throwException(new InvalidArgument('Missing required field "FlowDefinitionArn".'));
        $this->dataAttributes = isset($input['DataAttributes']) ? HumanLoopDataAttributes::create($input['DataAttributes']) : null;
    }

    /**
     * @param array{
     *   HumanLoopName: string,
     *   FlowDefinitionArn: string,
     *   DataAttributes?: HumanLoopDataAttributes|array|null,
     * }|HumanLoopConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataAttributes(): ?HumanLoopDataAttributes
    {
        return $this->dataAttributes;
    }

    public function getFlowDefinitionArn(): string
    {
        return $this->flowDefinitionArn;
    }

    public function getHumanLoopName(): string
    {
        return $this->humanLoopName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->humanLoopName;
        $payload['HumanLoopName'] = $v;
        $v = $this->flowDefinitionArn;
        $payload['FlowDefinitionArn'] = $v;
        if (null !== $v = $this->dataAttributes) {
            $payload['DataAttributes'] = $v->requestBody();
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
