<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The schedule's target.
 */
final class Target
{
    /**
     * The Amazon Resource Name (ARN) of the target.
     */
    private $arn;

    /**
     * An object that contains information about an Amazon SQS queue that EventBridge Scheduler uses as a dead-letter queue
     * for your schedule. If specified, EventBridge Scheduler delivers failed events that could not be successfully
     * delivered to a target to the queue.
     */
    private $deadLetterConfig;

    /**
     * The templated target type for the Amazon ECS `RunTask` API operation.
     *
     * @see https://docs.aws.amazon.com/AmazonECS/latest/APIReference/API_RunTask.html
     */
    private $ecsParameters;

    /**
     * The templated target type for the EventBridge `PutEvents` API operation.
     *
     * @see https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
     */
    private $eventBridgeParameters;

    /**
     * The text, or well-formed JSON, passed to the target. If you are configuring a templated Lambda, AWS Step Functions,
     * or Amazon EventBridge target, the input must be a well-formed JSON. For all other target types, a JSON is not
     * required. If you do not specify anything for this field, EventBridge Scheduler delivers a default notification to the
     * target.
     */
    private $input;

    /**
     * The templated target type for the Amazon Kinesis `PutRecord` API operation.
     *
     * @see kinesis/latest/APIReference/API_PutRecord.html
     */
    private $kinesisParameters;

    /**
     * A `RetryPolicy` object that includes information about the retry policy settings, including the maximum age of an
     * event, and the maximum number of times EventBridge Scheduler will try to deliver the event to a target.
     */
    private $retryPolicy;

    /**
     * The Amazon Resource Name (ARN) of the IAM role that EventBridge Scheduler will use for this target when the schedule
     * is invoked.
     */
    private $roleArn;

    /**
     * The templated target type for the Amazon SageMaker `StartPipelineExecution` API operation.
     *
     * @see https://docs.aws.amazon.com/sagemaker/latest/APIReference/API_StartPipelineExecution.html
     */
    private $sageMakerPipelineParameters;

    /**
     * The templated target type for the Amazon SQS `SendMessage` API operation. Contains the message group ID to use when
     * the target is a FIFO queue. If you specify an Amazon SQS FIFO queue as a target, the queue must have content-based
     * deduplication enabled. For more information, see Using the Amazon SQS message deduplication ID in the *Amazon SQS
     * Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagededuplicationid-property.html
     */
    private $sqsParameters;

    /**
     * @param array{
     *   Arn: string,
     *   DeadLetterConfig?: null|DeadLetterConfig|array,
     *   EcsParameters?: null|EcsParameters|array,
     *   EventBridgeParameters?: null|EventBridgeParameters|array,
     *   Input?: null|string,
     *   KinesisParameters?: null|KinesisParameters|array,
     *   RetryPolicy?: null|RetryPolicy|array,
     *   RoleArn: string,
     *   SageMakerPipelineParameters?: null|SageMakerPipelineParameters|array,
     *   SqsParameters?: null|SqsParameters|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->deadLetterConfig = isset($input['DeadLetterConfig']) ? DeadLetterConfig::create($input['DeadLetterConfig']) : null;
        $this->ecsParameters = isset($input['EcsParameters']) ? EcsParameters::create($input['EcsParameters']) : null;
        $this->eventBridgeParameters = isset($input['EventBridgeParameters']) ? EventBridgeParameters::create($input['EventBridgeParameters']) : null;
        $this->input = $input['Input'] ?? null;
        $this->kinesisParameters = isset($input['KinesisParameters']) ? KinesisParameters::create($input['KinesisParameters']) : null;
        $this->retryPolicy = isset($input['RetryPolicy']) ? RetryPolicy::create($input['RetryPolicy']) : null;
        $this->roleArn = $input['RoleArn'] ?? null;
        $this->sageMakerPipelineParameters = isset($input['SageMakerPipelineParameters']) ? SageMakerPipelineParameters::create($input['SageMakerPipelineParameters']) : null;
        $this->sqsParameters = isset($input['SqsParameters']) ? SqsParameters::create($input['SqsParameters']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): string
    {
        return $this->arn;
    }

    public function getDeadLetterConfig(): ?DeadLetterConfig
    {
        return $this->deadLetterConfig;
    }

    public function getEcsParameters(): ?EcsParameters
    {
        return $this->ecsParameters;
    }

    public function getEventBridgeParameters(): ?EventBridgeParameters
    {
        return $this->eventBridgeParameters;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function getKinesisParameters(): ?KinesisParameters
    {
        return $this->kinesisParameters;
    }

    public function getRetryPolicy(): ?RetryPolicy
    {
        return $this->retryPolicy;
    }

    public function getRoleArn(): string
    {
        return $this->roleArn;
    }

    public function getSageMakerPipelineParameters(): ?SageMakerPipelineParameters
    {
        return $this->sageMakerPipelineParameters;
    }

    public function getSqsParameters(): ?SqsParameters
    {
        return $this->sqsParameters;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->arn) {
            throw new InvalidArgument(sprintf('Missing parameter "Arn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Arn'] = $v;
        if (null !== $v = $this->deadLetterConfig) {
            $payload['DeadLetterConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->ecsParameters) {
            $payload['EcsParameters'] = $v->requestBody();
        }
        if (null !== $v = $this->eventBridgeParameters) {
            $payload['EventBridgeParameters'] = $v->requestBody();
        }
        if (null !== $v = $this->input) {
            $payload['Input'] = $v;
        }
        if (null !== $v = $this->kinesisParameters) {
            $payload['KinesisParameters'] = $v->requestBody();
        }
        if (null !== $v = $this->retryPolicy) {
            $payload['RetryPolicy'] = $v->requestBody();
        }
        if (null === $v = $this->roleArn) {
            throw new InvalidArgument(sprintf('Missing parameter "RoleArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RoleArn'] = $v;
        if (null !== $v = $this->sageMakerPipelineParameters) {
            $payload['SageMakerPipelineParameters'] = $v->requestBody();
        }
        if (null !== $v = $this->sqsParameters) {
            $payload['SqsParameters'] = $v->requestBody();
        }

        return $payload;
    }
}
