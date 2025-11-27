<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\MetricsName;

/**
 * Represents the output for EnableEnhancedMonitoring and DisableEnhancedMonitoring.
 */
class EnhancedMonitoringOutput extends Result
{
    /**
     * The name of the Kinesis data stream.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * Represents the current state of the metrics that are in the enhanced state before the operation.
     *
     * @var list<MetricsName::*>
     */
    private $currentShardLevelMetrics;

    /**
     * Represents the list of all the metrics that would be in the enhanced state after the operation.
     *
     * @var list<MetricsName::*>
     */
    private $desiredShardLevelMetrics;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @return list<MetricsName::*>
     */
    public function getCurrentShardLevelMetrics(): array
    {
        $this->initialize();

        return $this->currentShardLevelMetrics;
    }

    /**
     * @return list<MetricsName::*>
     */
    public function getDesiredShardLevelMetrics(): array
    {
        $this->initialize();

        return $this->desiredShardLevelMetrics;
    }

    public function getStreamArn(): ?string
    {
        $this->initialize();

        return $this->streamArn;
    }

    public function getStreamName(): ?string
    {
        $this->initialize();

        return $this->streamName;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->streamName = isset($data['StreamName']) ? (string) $data['StreamName'] : null;
        $this->currentShardLevelMetrics = empty($data['CurrentShardLevelMetrics']) ? [] : $this->populateResultMetricsNameList($data['CurrentShardLevelMetrics']);
        $this->desiredShardLevelMetrics = empty($data['DesiredShardLevelMetrics']) ? [] : $this->populateResultMetricsNameList($data['DesiredShardLevelMetrics']);
        $this->streamArn = isset($data['StreamARN']) ? (string) $data['StreamARN'] : null;
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
                if (!MetricsName::exists($a)) {
                    $a = MetricsName::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }
}
