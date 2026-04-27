<?php

namespace AsyncAws\Sqs\ValueObject;

/**
 * Contains the details of a message movement task.
 */
final class ListMessageMoveTasksResultEntry
{
    /**
     * An identifier associated with a message movement task. When this field is returned in the response of the
     * `ListMessageMoveTasks` action, it is only populated for tasks that are in RUNNING status.
     *
     * @var string|null
     */
    private $taskHandle;

    /**
     * The status of the message movement task. Possible values are: RUNNING, COMPLETED, CANCELLING, CANCELLED, and FAILED.
     *
     * @var string|null
     */
    private $status;

    /**
     * The ARN of the queue that contains the messages to be moved to another queue.
     *
     * @var string|null
     */
    private $sourceArn;

    /**
     * The ARN of the destination queue if it has been specified in the `StartMessageMoveTask` request. If a
     * `DestinationArn` has not been specified in the `StartMessageMoveTask` request, this field value will be NULL.
     *
     * @var string|null
     */
    private $destinationArn;

    /**
     * The number of messages to be moved per second (the message movement rate), if it has been specified in the
     * `StartMessageMoveTask` request. If a `MaxNumberOfMessagesPerSecond` has not been specified in the
     * `StartMessageMoveTask` request, this field value will be NULL.
     *
     * @var int|null
     */
    private $maxNumberOfMessagesPerSecond;

    /**
     * The approximate number of messages already moved to the destination queue.
     *
     * @var int|null
     */
    private $approximateNumberOfMessagesMoved;

    /**
     * The number of messages to be moved from the source queue. This number is obtained at the time of starting the message
     * movement task and is only included after the message movement task is selected to start.
     *
     * @var int|null
     */
    private $approximateNumberOfMessagesToMove;

    /**
     * The task failure reason (only included if the task status is FAILED).
     *
     * @var string|null
     */
    private $failureReason;

    /**
     * The timestamp of starting the message movement task.
     *
     * @var int|null
     */
    private $startedTimestamp;

    /**
     * @param array{
     *   TaskHandle?: string|null,
     *   Status?: string|null,
     *   SourceArn?: string|null,
     *   DestinationArn?: string|null,
     *   MaxNumberOfMessagesPerSecond?: int|null,
     *   ApproximateNumberOfMessagesMoved?: int|null,
     *   ApproximateNumberOfMessagesToMove?: int|null,
     *   FailureReason?: string|null,
     *   StartedTimestamp?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->taskHandle = $input['TaskHandle'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->sourceArn = $input['SourceArn'] ?? null;
        $this->destinationArn = $input['DestinationArn'] ?? null;
        $this->maxNumberOfMessagesPerSecond = $input['MaxNumberOfMessagesPerSecond'] ?? null;
        $this->approximateNumberOfMessagesMoved = $input['ApproximateNumberOfMessagesMoved'] ?? null;
        $this->approximateNumberOfMessagesToMove = $input['ApproximateNumberOfMessagesToMove'] ?? null;
        $this->failureReason = $input['FailureReason'] ?? null;
        $this->startedTimestamp = $input['StartedTimestamp'] ?? null;
    }

    /**
     * @param array{
     *   TaskHandle?: string|null,
     *   Status?: string|null,
     *   SourceArn?: string|null,
     *   DestinationArn?: string|null,
     *   MaxNumberOfMessagesPerSecond?: int|null,
     *   ApproximateNumberOfMessagesMoved?: int|null,
     *   ApproximateNumberOfMessagesToMove?: int|null,
     *   FailureReason?: string|null,
     *   StartedTimestamp?: int|null,
     * }|ListMessageMoveTasksResultEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApproximateNumberOfMessagesMoved(): ?int
    {
        return $this->approximateNumberOfMessagesMoved;
    }

    public function getApproximateNumberOfMessagesToMove(): ?int
    {
        return $this->approximateNumberOfMessagesToMove;
    }

    public function getDestinationArn(): ?string
    {
        return $this->destinationArn;
    }

    public function getFailureReason(): ?string
    {
        return $this->failureReason;
    }

    public function getMaxNumberOfMessagesPerSecond(): ?int
    {
        return $this->maxNumberOfMessagesPerSecond;
    }

    public function getSourceArn(): ?string
    {
        return $this->sourceArn;
    }

    public function getStartedTimestamp(): ?int
    {
        return $this->startedTimestamp;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getTaskHandle(): ?string
    {
        return $this->taskHandle;
    }
}
