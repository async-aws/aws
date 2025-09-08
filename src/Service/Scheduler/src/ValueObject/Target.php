<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The schedule's target. EventBridge Scheduler supports templated target that invoke common API operations, as well as
 * universal targets that you can customize to invoke over 6,000 API operations across more than 270 services. You can
 * only specify one templated or universal target for a schedule.
 */
final class Target
{
    /**
     * The Amazon Resource Name (ARN) of the target.
     *
     * @var string
     */
    private $arn;

    /**
     * An object that contains information about an Amazon SQS queue that EventBridge Scheduler uses as a dead-letter queue
     * for your schedule. If specified, EventBridge Scheduler delivers failed events that could not be successfully
     * delivered to a target to the queue.
     *
     * @var DeadLetterConfig|null
     */
    private $deadLetterConfig;

    /**
     * The templated target type for the Amazon ECS `RunTask` [^1] API operation.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonECS/latest/APIReference/API_RunTask.html
     *
     * @var EcsParameters|null
     */
    private $ecsParameters;

    /**
     * The templated target type for the EventBridge `PutEvents` [^1] API operation.
     *
     * [^1]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
     *
     * @var EventBridgeParameters|null
     */
    private $eventBridgeParameters;

    /**
     * The text, or well-formed JSON, passed to the target. If you are configuring a templated Lambda, AWS Step Functions,
     * or Amazon EventBridge target, the input must be a well-formed JSON. For all other target types, a JSON is not
     * required. If you do not specify anything for this field, EventBridge Scheduler delivers a default notification to the
     * target.
     *
     * @var string|null
     */
    private $input;

    /**
     * The templated target type for the Amazon Kinesis `PutRecord` [^1] API operation.
     *
     * [^1]: kinesis/latest/APIReference/API_PutRecord.html
     *
     * @var KinesisParameters|null
     */
    private $kinesisParameters;

    /**
     * A `RetryPolicy` object that includes information about the retry policy settings, including the maximum age of an
     * event, and the maximum number of times EventBridge Scheduler will try to deliver the event to a target.
     *
     * @var RetryPolicy|null
     */
    private $retryPolicy;

    /**
     * The Amazon Resource Name (ARN) of the IAM role that EventBridge Scheduler will use for this target when the schedule
     * is invoked.
     *
     * @var string
     */
    private $roleArn;

    /**
     * The templated target type for the Amazon SageMaker `StartPipelineExecution` [^1] API operation.
     *
     * [^1]: https://docs.aws.amazon.com/sagemaker/latest/APIReference/API_StartPipelineExecution.html
     *
     * @var SageMakerPipelineParameters|null
     */
    private $sageMakerPipelineParameters;

    /**
     * The templated target type for the Amazon SQS `SendMessage` [^1] API operation. Contains the message group ID to use
     * when the target is a FIFO queue. If you specify an Amazon SQS FIFO queue as a target, the queue must have
     * content-based deduplication enabled. For more information, see Using the Amazon SQS message deduplication ID [^2] in
     * the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagededuplicationid-property.html
     *
     * @var SqsParameters|null
     */
    private $sqsParameters;

    /**
     * @param array{
     *   Arn: string,
     *   DeadLetterConfig?: DeadLetterConfig|array|null,
     *   EcsParameters?: EcsParameters|array|null,
     *   EventBridgeParameters?: EventBridgeParameters|array|null,
     *   Input?: string|null,
     *   KinesisParameters?: KinesisParameters|array|null,
     *   RetryPolicy?: RetryPolicy|array|null,
     *   RoleArn: string,
     *   SageMakerPipelineParameters?: SageMakerPipelineParameters|array|null,
     *   SqsParameters?: SqsParameters|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? $this->throwException(new InvalidArgument('Missing required field "Arn".'));
        $this->deadLetterConfig = isset($input['DeadLetterConfig']) ? DeadLetterConfig::create($input['DeadLetterConfig']) : null;
        $this->ecsParameters = isset($input['EcsParameters']) ? EcsParameters::create($input['EcsParameters']) : null;
        $this->eventBridgeParameters = isset($input['EventBridgeParameters']) ? EventBridgeParameters::create($input['EventBridgeParameters']) : null;
        $this->input = $input['Input'] ?? null;
        $this->kinesisParameters = isset($input['KinesisParameters']) ? KinesisParameters::create($input['KinesisParameters']) : null;
        $this->retryPolicy = isset($input['RetryPolicy']) ? RetryPolicy::create($input['RetryPolicy']) : null;
        $this->roleArn = $input['RoleArn'] ?? $this->throwException(new InvalidArgument('Missing required field "RoleArn".'));
        $this->sageMakerPipelineParameters = isset($input['SageMakerPipelineParameters']) ? SageMakerPipelineParameters::create($input['SageMakerPipelineParameters']) : null;
        $this->sqsParameters = isset($input['SqsParameters']) ? SqsParameters::create($input['SqsParameters']) : null;
    }

    /**
     * @param array{
     *   Arn: string,
     *   DeadLetterConfig?: DeadLetterConfig|array|null,
     *   EcsParameters?: EcsParameters|array|null,
     *   EventBridgeParameters?: EventBridgeParameters|array|null,
     *   Input?: string|null,
     *   KinesisParameters?: KinesisParameters|array|null,
     *   RetryPolicy?: RetryPolicy|array|null,
     *   RoleArn: string,
     *   SageMakerPipelineParameters?: SageMakerPipelineParameters|array|null,
     *   SqsParameters?: SqsParameters|array|null,
     * }|Target $input
     */
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
        $v = $this->arn;
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
        $v = $this->roleArn;
        $payload['RoleArn'] = $v;
        if (null !== $v = $this->sageMakerPipelineParameters) {
            $payload['SageMakerPipelineParameters'] = $v->requestBody();
        }
        if (null !== $v = $this->sqsParameters) {
            $payload['SqsParameters'] = $v->requestBody();
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
