<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\ValueObject\EnhancedMetrics;
use AsyncAws\Kinesis\ValueObject\StreamDescriptionSummary;

class DescribeStreamSummaryOutput extends Result
{
    /**
     * A StreamDescriptionSummary containing information about the stream.
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

        $this->streamDescriptionSummary = new StreamDescriptionSummary([
            'StreamName' => (string) $data['StreamDescriptionSummary']['StreamName'],
            'StreamARN' => (string) $data['StreamDescriptionSummary']['StreamARN'],
            'StreamStatus' => (string) $data['StreamDescriptionSummary']['StreamStatus'],
            'RetentionPeriodHours' => (int) $data['StreamDescriptionSummary']['RetentionPeriodHours'],
            'StreamCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['StreamDescriptionSummary']['StreamCreationTimestamp'])),
            'EnhancedMonitoring' => $this->populateResultEnhancedMonitoringList($data['StreamDescriptionSummary']['EnhancedMonitoring']),
            'EncryptionType' => isset($data['StreamDescriptionSummary']['EncryptionType']) ? (string) $data['StreamDescriptionSummary']['EncryptionType'] : null,
            'KeyId' => isset($data['StreamDescriptionSummary']['KeyId']) ? (string) $data['StreamDescriptionSummary']['KeyId'] : null,
            'OpenShardCount' => (int) $data['StreamDescriptionSummary']['OpenShardCount'],
            'ConsumerCount' => isset($data['StreamDescriptionSummary']['ConsumerCount']) ? (int) $data['StreamDescriptionSummary']['ConsumerCount'] : null,
        ]);
    }

    /**
     * @return EnhancedMetrics[]
     */
    private function populateResultEnhancedMonitoringList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new EnhancedMetrics([
                'ShardLevelMetrics' => !isset($item['ShardLevelMetrics']) ? null : $this->populateResultMetricsNameList($item['ShardLevelMetrics']),
            ]);
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
}
