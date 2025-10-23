<?php

namespace AsyncAws\Scheduler\ValueObject;

/**
 * A `RetryPolicy` object that includes information about the retry policy settings, including the maximum age of an
 * event, and the maximum number of times EventBridge Scheduler will try to deliver the event to a target.
 */
final class RetryPolicy
{
    /**
     * The maximum amount of time, in seconds, to continue to make retry attempts.
     *
     * @var int|null
     */
    private $maximumEventAgeInSeconds;

    /**
     * The maximum number of retry attempts to make before the request fails. Retry attempts with exponential backoff
     * continue until either the maximum number of attempts is made or until the duration of the `MaximumEventAgeInSeconds`
     * is reached.
     *
     * @var int|null
     */
    private $maximumRetryAttempts;

    /**
     * @param array{
     *   MaximumEventAgeInSeconds?: int|null,
     *   MaximumRetryAttempts?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maximumEventAgeInSeconds = $input['MaximumEventAgeInSeconds'] ?? null;
        $this->maximumRetryAttempts = $input['MaximumRetryAttempts'] ?? null;
    }

    /**
     * @param array{
     *   MaximumEventAgeInSeconds?: int|null,
     *   MaximumRetryAttempts?: int|null,
     * }|RetryPolicy $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaximumEventAgeInSeconds(): ?int
    {
        return $this->maximumEventAgeInSeconds;
    }

    public function getMaximumRetryAttempts(): ?int
    {
        return $this->maximumRetryAttempts;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maximumEventAgeInSeconds) {
            $payload['MaximumEventAgeInSeconds'] = $v;
        }
        if (null !== $v = $this->maximumRetryAttempts) {
            $payload['MaximumRetryAttempts'] = $v;
        }

        return $payload;
    }
}
