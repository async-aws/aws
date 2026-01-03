<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AccelerationMode;

/**
 * Accelerated transcoding can significantly speed up jobs with long, visually complex content.
 */
final class AccelerationSettings
{
    /**
     * Specify the conditions when the service will run your job with accelerated transcoding.
     *
     * @var AccelerationMode::*
     */
    private $mode;

    /**
     * @param array{
     *   Mode: AccelerationMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? $this->throwException(new InvalidArgument('Missing required field "Mode".'));
    }

    /**
     * @param array{
     *   Mode: AccelerationMode::*,
     * }|AccelerationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AccelerationMode::*
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
        $v = $this->mode;
        if (!AccelerationMode::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "mode" for "%s". The value "%s" is not a valid "AccelerationMode".', __CLASS__, $v));
        }
        $payload['mode'] = $v;

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
