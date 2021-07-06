<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\Enum\MetricsName;

/**
 * Represents the input for DisableEnhancedMonitoring.
 */
final class DisableEnhancedMonitoringInput extends Input
{
    /**
     * The name of the Kinesis data stream for which to disable enhanced monitoring.
     *
     * @required
     *
     * @var string|null
     */
    private $streamName;

    /**
     * List of shard-level metrics to disable.
     *
     * @required
     *
     * @var null|list<MetricsName::*>
     */
    private $shardLevelMetrics;

    /**
     * @param array{
     *   StreamName?: string,
     *   ShardLevelMetrics?: list<MetricsName::*>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardLevelMetrics = $input['ShardLevelMetrics'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<MetricsName::*>
     */
    public function getShardLevelMetrics(): array
    {
        return $this->shardLevelMetrics ?? [];
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.DisableEnhancedMonitoring',
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
     * @param list<MetricsName::*> $value
     */
    public function setShardLevelMetrics(array $value): self
    {
        $this->shardLevelMetrics = $value;

        return $this;
    }

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamName) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null === $v = $this->shardLevelMetrics) {
            throw new InvalidArgument(sprintf('Missing parameter "ShardLevelMetrics" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['ShardLevelMetrics'] = [];
        foreach ($v as $listValue) {
            ++$index;
            if (!MetricsName::exists($listValue)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ShardLevelMetrics" for "%s". The value "%s" is not a valid "MetricsName".', __CLASS__, $listValue));
            }
            $payload['ShardLevelMetrics'][$index] = $listValue;
        }

        return $payload;
    }
}
