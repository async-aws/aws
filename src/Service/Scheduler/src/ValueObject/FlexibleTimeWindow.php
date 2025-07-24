<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Scheduler\Enum\FlexibleTimeWindowMode;

/**
 * Allows you to configure a time window during which EventBridge Scheduler invokes the schedule.
 */
final class FlexibleTimeWindow
{
    /**
     * The maximum time window during which a schedule can be invoked.
     *
     * @var int|null
     */
    private $maximumWindowInMinutes;

    /**
     * Determines whether the schedule is invoked within a flexible time window.
     *
     * @var FlexibleTimeWindowMode::*|string
     */
    private $mode;

    /**
     * @param array{
     *   MaximumWindowInMinutes?: null|int,
     *   Mode: FlexibleTimeWindowMode::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maximumWindowInMinutes = $input['MaximumWindowInMinutes'] ?? null;
        $this->mode = $input['Mode'] ?? $this->throwException(new InvalidArgument('Missing required field "Mode".'));
    }

    /**
     * @param array{
     *   MaximumWindowInMinutes?: null|int,
     *   Mode: FlexibleTimeWindowMode::*|string,
     * }|FlexibleTimeWindow $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaximumWindowInMinutes(): ?int
    {
        return $this->maximumWindowInMinutes;
    }

    /**
     * @return FlexibleTimeWindowMode::*|string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maximumWindowInMinutes) {
            $payload['MaximumWindowInMinutes'] = $v;
        }
        $v = $this->mode;
        if (!FlexibleTimeWindowMode::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "Mode" for "%s". The value "%s" is not a valid "FlexibleTimeWindowMode".', __CLASS__, $v));
        }
        $payload['Mode'] = $v;

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
