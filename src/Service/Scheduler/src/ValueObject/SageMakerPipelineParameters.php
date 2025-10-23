<?php

namespace AsyncAws\Scheduler\ValueObject;

/**
 * The templated target type for the Amazon SageMaker `StartPipelineExecution` [^1] API operation.
 *
 * [^1]: https://docs.aws.amazon.com/sagemaker/latest/APIReference/API_StartPipelineExecution.html
 */
final class SageMakerPipelineParameters
{
    /**
     * List of parameter names and values to use when executing the SageMaker Model Building Pipeline.
     *
     * @var SageMakerPipelineParameter[]|null
     */
    private $pipelineParameterList;

    /**
     * @param array{
     *   PipelineParameterList?: array<SageMakerPipelineParameter|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->pipelineParameterList = isset($input['PipelineParameterList']) ? array_map([SageMakerPipelineParameter::class, 'create'], $input['PipelineParameterList']) : null;
    }

    /**
     * @param array{
     *   PipelineParameterList?: array<SageMakerPipelineParameter|array>|null,
     * }|SageMakerPipelineParameters $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SageMakerPipelineParameter[]
     */
    public function getPipelineParameterList(): array
    {
        return $this->pipelineParameterList ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->pipelineParameterList) {
            $index = -1;
            $payload['PipelineParameterList'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['PipelineParameterList'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
