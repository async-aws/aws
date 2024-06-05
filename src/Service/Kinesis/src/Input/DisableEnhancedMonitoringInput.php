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
     * @var string|null
     */
    private $streamName;

    /**
     * List of shard-level metrics to disable.
     *
     * The following are the valid shard-level metrics. The value "`ALL`" disables every metric.
     *
     * - `IncomingBytes`
     * - `IncomingRecords`
     * - `OutgoingBytes`
     * - `OutgoingRecords`
     * - `WriteProvisionedThroughputExceeded`
     * - `ReadProvisionedThroughputExceeded`
     * - `IteratorAgeMilliseconds`
     * - `ALL`
     *
     * For more information, see Monitoring the Amazon Kinesis Data Streams Service with Amazon CloudWatch [^1] in the
     * *Amazon Kinesis Data Streams Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/monitoring-with-cloudwatch.html
     *
     * @required
     *
     * @var list<MetricsName::*>|null
     */
    private $shardLevelMetrics;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: null|string,
     *   ShardLevelMetrics?: array<MetricsName::*>,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardLevelMetrics = $input['ShardLevelMetrics'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: null|string,
     *   ShardLevelMetrics?: array<MetricsName::*>,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DisableEnhancedMonitoringInput $input
     */
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

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
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
            'Accept' => 'application/json',
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

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

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
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
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

        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
