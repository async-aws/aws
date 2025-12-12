<?php

namespace AsyncAws\CloudWatch\Result;

use AsyncAws\CloudWatch\ValueObject\Datapoint;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetMetricStatisticsOutput extends Result
{
    /**
     * A label for the specified metric.
     *
     * @var string|null
     */
    private $label;

    /**
     * The data points for the specified metric.
     *
     * @var Datapoint[]
     */
    private $datapoints;

    /**
     * @return Datapoint[]
     */
    public function getDatapoints(): array
    {
        $this->initialize();

        return $this->datapoints;
    }

    public function getLabel(): ?string
    {
        $this->initialize();

        return $this->label;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->label = isset($data['Label']) ? (string) $data['Label'] : null;
        $this->datapoints = empty($data['Datapoints']) ? [] : $this->populateResultDatapoints($data['Datapoints']);
    }

    private function populateResultDatapoint(array $json): Datapoint
    {
        return new Datapoint([
            'Timestamp' => (isset($json['Timestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['Timestamp'])))) ? $d : null,
            'SampleCount' => isset($json['SampleCount']) ? (float) $json['SampleCount'] : null,
            'Average' => isset($json['Average']) ? (float) $json['Average'] : null,
            'Sum' => isset($json['Sum']) ? (float) $json['Sum'] : null,
            'Minimum' => isset($json['Minimum']) ? (float) $json['Minimum'] : null,
            'Maximum' => isset($json['Maximum']) ? (float) $json['Maximum'] : null,
            'Unit' => isset($json['Unit']) ? (string) $json['Unit'] : null,
            'ExtendedStatistics' => !isset($json['ExtendedStatistics']) ? null : $this->populateResultDatapointValueMap($json['ExtendedStatistics']),
        ]);
    }

    /**
     * @return array<string, float>
     */
    private function populateResultDatapointValueMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (float) $value;
        }

        return $items;
    }

    /**
     * @return Datapoint[]
     */
    private function populateResultDatapoints(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDatapoint($item);
        }

        return $items;
    }
}
