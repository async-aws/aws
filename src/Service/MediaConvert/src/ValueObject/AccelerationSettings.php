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
     */
    private $mode;

    /**
     * @param array{
     *   Mode: AccelerationMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? null;
    }

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
        if (null === $v = $this->mode) {
            throw new InvalidArgument(sprintf('Missing parameter "Mode" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!AccelerationMode::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "mode" for "%s". The value "%s" is not a valid "AccelerationMode".', __CLASS__, $v));
        }
        $payload['mode'] = $v;

        return $payload;
    }
}
