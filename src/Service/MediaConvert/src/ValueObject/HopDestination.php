<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Optional. Configuration for a destination queue to which the job can hop once a customer-defined minimum wait time
 * has passed.
 */
final class HopDestination
{
    /**
     * Optional. When you set up a job to use queue hopping, you can specify a different relative priority for the job in
     * the destination queue. If you don't specify, the relative priority will remain the same as in the previous queue.
     *
     * @var int|null
     */
    private $priority;

    /**
     * Optional unless the job is submitted on the default queue. When you set up a job to use queue hopping, you can
     * specify a destination queue. This queue cannot be the original queue to which the job is submitted. If the original
     * queue isn't the default queue and you don't specify the destination queue, the job will move to the default queue.
     *
     * @var string|null
     */
    private $queue;

    /**
     * Required for setting up a job to use queue hopping. Minimum wait time in minutes until the job can hop to the
     * destination queue. Valid range is 1 to 4320 minutes, inclusive.
     *
     * @var int|null
     */
    private $waitMinutes;

    /**
     * @param array{
     *   Priority?: int|null,
     *   Queue?: string|null,
     *   WaitMinutes?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->priority = $input['Priority'] ?? null;
        $this->queue = $input['Queue'] ?? null;
        $this->waitMinutes = $input['WaitMinutes'] ?? null;
    }

    /**
     * @param array{
     *   Priority?: int|null,
     *   Queue?: string|null,
     *   WaitMinutes?: int|null,
     * }|HopDestination $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function getWaitMinutes(): ?int
    {
        return $this->waitMinutes;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->priority) {
            $payload['priority'] = $v;
        }
        if (null !== $v = $this->queue) {
            $payload['queue'] = $v;
        }
        if (null !== $v = $this->waitMinutes) {
            $payload['waitMinutes'] = $v;
        }

        return $payload;
    }
}
