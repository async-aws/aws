<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ImageStatus;

/**
 * Contains details about the failure when the image creation process fails. Properties appear in the failure context
 * when the related information is available for the failure.
 */
final class ImageFailureContext
{
    /**
     * The status that the image had when the failure occurred. This indicates the stage of the image creation process where
     * the image failed, for example `BUILDING` or `DISTRIBUTING`.
     *
     * @var ImageStatus::*|null
     */
    private $imageStatus;

    /**
     * The unique identifier of the workflow execution that was running when the image failed.
     *
     * @var string|null
     */
    private $workflowExecutionId;

    /**
     * The Amazon Resource Name (ARN) of the workflow build version that was running when the image failed.
     *
     * @var string|null
     */
    private $workflowArn;

    /**
     * The unique identifier of the workflow step execution that failed.
     *
     * @var string|null
     */
    private $stepExecutionId;

    /**
     * The name of the workflow step that failed, as it appears in the workflow document.
     *
     * @var string|null
     */
    private $failedStep;

    /**
     * The details about the component that failed, if the failure occurred while a component was running.
     *
     * @var ComponentFailureContext|null
     */
    private $componentFailure;

    /**
     * The details about the distribution failure, if the failure occurred while Image Builder distributed or configured the
     * image.
     *
     * @var DistributionFailureContext|null
     */
    private $distributionFailure;

    /**
     * @param array{
     *   imageStatus?: ImageStatus::*|null,
     *   workflowExecutionId?: string|null,
     *   workflowArn?: string|null,
     *   stepExecutionId?: string|null,
     *   failedStep?: string|null,
     *   componentFailure?: ComponentFailureContext|array|null,
     *   distributionFailure?: DistributionFailureContext|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->imageStatus = $input['imageStatus'] ?? null;
        $this->workflowExecutionId = $input['workflowExecutionId'] ?? null;
        $this->workflowArn = $input['workflowArn'] ?? null;
        $this->stepExecutionId = $input['stepExecutionId'] ?? null;
        $this->failedStep = $input['failedStep'] ?? null;
        $this->componentFailure = isset($input['componentFailure']) ? ComponentFailureContext::create($input['componentFailure']) : null;
        $this->distributionFailure = isset($input['distributionFailure']) ? DistributionFailureContext::create($input['distributionFailure']) : null;
    }

    /**
     * @param array{
     *   imageStatus?: ImageStatus::*|null,
     *   workflowExecutionId?: string|null,
     *   workflowArn?: string|null,
     *   stepExecutionId?: string|null,
     *   failedStep?: string|null,
     *   componentFailure?: ComponentFailureContext|array|null,
     *   distributionFailure?: DistributionFailureContext|array|null,
     * }|ImageFailureContext $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getComponentFailure(): ?ComponentFailureContext
    {
        return $this->componentFailure;
    }

    public function getDistributionFailure(): ?DistributionFailureContext
    {
        return $this->distributionFailure;
    }

    public function getFailedStep(): ?string
    {
        return $this->failedStep;
    }

    /**
     * @return ImageStatus::*|null
     */
    public function getImageStatus(): ?string
    {
        return $this->imageStatus;
    }

    public function getStepExecutionId(): ?string
    {
        return $this->stepExecutionId;
    }

    public function getWorkflowArn(): ?string
    {
        return $this->workflowArn;
    }

    public function getWorkflowExecutionId(): ?string
    {
        return $this->workflowExecutionId;
    }
}
