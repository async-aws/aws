<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\Enum\ScalingType;

final class UpdateShardCountInput extends Input
{
    /**
     * The name of the stream.
     *
     * @required
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The new number of shards. This value has the following default limits. By default, you cannot do the following:.
     *
     * @required
     *
     * @var int|null
     */
    private $targetShardCount;

    /**
     * The scaling type. Uniform scaling creates shards of equal size.
     *
     * @required
     *
     * @var ScalingType::*|null
     */
    private $scalingType;

    /**
     * @param array{
     *   StreamName?: string,
     *   TargetShardCount?: int,
     *   ScalingType?: ScalingType::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->targetShardCount = $input['TargetShardCount'] ?? null;
        $this->scalingType = $input['ScalingType'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ScalingType::*|null
     */
    public function getScalingType(): ?string
    {
        return $this->scalingType;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    public function getTargetShardCount(): ?int
    {
        return $this->targetShardCount;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.UpdateShardCount',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ScalingType::*|null $value
     */
    public function setScalingType(?string $value): self
    {
        $this->scalingType = $value;

        return $this;
    }

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    public function setTargetShardCount(?int $value): self
    {
        $this->targetShardCount = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamName) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null === $v = $this->targetShardCount) {
            throw new InvalidArgument(sprintf('Missing parameter "TargetShardCount" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TargetShardCount'] = $v;
        if (null === $v = $this->scalingType) {
            throw new InvalidArgument(sprintf('Missing parameter "ScalingType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ScalingType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "ScalingType" for "%s". The value "%s" is not a valid "ScalingType".', __CLASS__, $v));
        }
        $payload['ScalingType'] = $v;

        return $payload;
    }
}
