<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kinesis\Enum\StreamStatus;

/**
 * The summary of a stream.
 */
final class StreamSummary
{
    /**
     * The name of a stream.
     *
     * @var string
     */
    private $streamName;

    /**
     * The ARN of the stream.
     *
     * @var string
     */
    private $streamArn;

    /**
     * The status of the stream.
     *
     * @var StreamStatus::*
     */
    private $streamStatus;

    /**
     * @var StreamModeDetails|null
     */
    private $streamModeDetails;

    /**
     * The timestamp at which the stream was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $streamCreationTimestamp;

    /**
     * @param array{
     *   StreamName: string,
     *   StreamARN: string,
     *   StreamStatus: StreamStatus::*,
     *   StreamModeDetails?: null|StreamModeDetails|array,
     *   StreamCreationTimestamp?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->streamName = $input['StreamName'] ?? $this->throwException(new InvalidArgument('Missing required field "StreamName".'));
        $this->streamArn = $input['StreamARN'] ?? $this->throwException(new InvalidArgument('Missing required field "StreamARN".'));
        $this->streamStatus = $input['StreamStatus'] ?? $this->throwException(new InvalidArgument('Missing required field "StreamStatus".'));
        $this->streamModeDetails = isset($input['StreamModeDetails']) ? StreamModeDetails::create($input['StreamModeDetails']) : null;
        $this->streamCreationTimestamp = $input['StreamCreationTimestamp'] ?? null;
    }

    /**
     * @param array{
     *   StreamName: string,
     *   StreamARN: string,
     *   StreamStatus: StreamStatus::*,
     *   StreamModeDetails?: null|StreamModeDetails|array,
     *   StreamCreationTimestamp?: null|\DateTimeImmutable,
     * }|StreamSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStreamArn(): string
    {
        return $this->streamArn;
    }

    public function getStreamCreationTimestamp(): ?\DateTimeImmutable
    {
        return $this->streamCreationTimestamp;
    }

    public function getStreamModeDetails(): ?StreamModeDetails
    {
        return $this->streamModeDetails;
    }

    public function getStreamName(): string
    {
        return $this->streamName;
    }

    /**
     * @return StreamStatus::*
     */
    public function getStreamStatus(): string
    {
        return $this->streamStatus;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
