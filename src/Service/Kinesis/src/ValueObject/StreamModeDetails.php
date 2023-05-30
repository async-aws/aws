<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kinesis\Enum\StreamMode;

/**
 * Specifies the capacity mode to which you want to set your data stream. Currently, in Kinesis Data Streams, you can
 * choose between an **on-demand** capacity mode and a **provisioned** capacity mode for your data streams.
 */
final class StreamModeDetails
{
    /**
     * Specifies the capacity mode to which you want to set your data stream. Currently, in Kinesis Data Streams, you can
     * choose between an **on-demand** capacity mode and a **provisioned** capacity mode for your data streams.
     */
    private $streamMode;

    /**
     * @param array{
     *   StreamMode: StreamMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->streamMode = $input['StreamMode'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return StreamMode::*
     */
    public function getStreamMode(): string
    {
        return $this->streamMode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamMode) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamMode" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!StreamMode::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "StreamMode" for "%s". The value "%s" is not a valid "StreamMode".', __CLASS__, $v));
        }
        $payload['StreamMode'] = $v;

        return $payload;
    }
}
