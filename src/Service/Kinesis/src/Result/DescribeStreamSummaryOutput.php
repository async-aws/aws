<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\ValueObject\EnhancedMetrics;
use AsyncAws\Kinesis\ValueObject\StreamDescriptionSummary;
use AsyncAws\Kinesis\ValueObject\StreamModeDetails;
use AsyncAws\Kinesis\ValueObject\WarmThroughputObject;

class DescribeStreamSummaryOutput extends Result
{
    /**
     * A StreamDescriptionSummary containing information about the stream.
     *
     * @var StreamDescriptionSummary
     */
    private $streamDescriptionSummary;

    public function getStreamDescriptionSummary(): StreamDescriptionSummary
    {
        $this->initialize();

        return $this->streamDescriptionSummary;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->streamDescriptionSummary = $this->populateResultStreamDescriptionSummary($data['StreamDescriptionSummary']);
    }

    private function populateResultEnhancedMetrics(array $json): EnhancedMetrics
    {
        return new EnhancedMetrics([
            'ShardLevelMetrics' => !isset($json['ShardLevelMetrics']) ? null : $this->populateResultMetricsNameList($json['ShardLevelMetrics']),
        ]);
    }

    /**
     * @return EnhancedMetrics[]
     */
    private function populateResultEnhancedMonitoringList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEnhancedMetrics($item);
        }

        return $items;
    }

    /**
     * @return list<MetricsName::*>
     */
    private function populateResultMetricsNameList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultStreamDescriptionSummary(array $json): StreamDescriptionSummary
    {
        return new StreamDescriptionSummary([
            'StreamName' => (string) $json['StreamName'],
            'StreamARN' => (string) $json['StreamARN'],
            'StreamStatus' => (string) $json['StreamStatus'],
            'StreamModeDetails' => empty($json['StreamModeDetails']) ? null : $this->populateResultStreamModeDetails($json['StreamModeDetails']),
            'RetentionPeriodHours' => (int) $json['RetentionPeriodHours'],
            'StreamCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['StreamCreationTimestamp'])),
            'EnhancedMonitoring' => $this->populateResultEnhancedMonitoringList($json['EnhancedMonitoring']),
            'EncryptionType' => isset($json['EncryptionType']) ? (string) $json['EncryptionType'] : null,
            'KeyId' => isset($json['KeyId']) ? (string) $json['KeyId'] : null,
            'OpenShardCount' => (int) $json['OpenShardCount'],
            'ConsumerCount' => isset($json['ConsumerCount']) ? (int) $json['ConsumerCount'] : null,
            'WarmThroughput' => empty($json['WarmThroughput']) ? null : $this->populateResultWarmThroughputObject($json['WarmThroughput']),
            'MaxRecordSizeInKiB' => isset($json['MaxRecordSizeInKiB']) ? (int) $json['MaxRecordSizeInKiB'] : null,
        ]);
    }

    private function populateResultStreamModeDetails(array $json): StreamModeDetails
    {
        return new StreamModeDetails([
            'StreamMode' => (string) $json['StreamMode'],
        ]);
    }

    private function populateResultWarmThroughputObject(array $json): WarmThroughputObject
    {
        return new WarmThroughputObject([
            'TargetMiBps' => isset($json['TargetMiBps']) ? (int) $json['TargetMiBps'] : null,
            'CurrentMiBps' => isset($json['CurrentMiBps']) ? (int) $json['CurrentMiBps'] : null,
        ]);
    }
}
