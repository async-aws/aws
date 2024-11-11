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
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->GetMetricStatisticsResult;

        $this->label = (null !== $v = $data->Label[0]) ? (string) $v : null;
        $this->datapoints = (0 === ($v = $data->Datapoints)->count()) ? [] : $this->populateResultDatapoints($v);
    }

    private function populateResultDatapoint(\SimpleXMLElement $xml): Datapoint
    {
        return new Datapoint([
            'Timestamp' => (null !== $v = $xml->Timestamp[0]) ? new \DateTimeImmutable((string) $v) : null,
            'SampleCount' => (null !== $v = $xml->SampleCount[0]) ? (float) (string) $v : null,
            'Average' => (null !== $v = $xml->Average[0]) ? (float) (string) $v : null,
            'Sum' => (null !== $v = $xml->Sum[0]) ? (float) (string) $v : null,
            'Minimum' => (null !== $v = $xml->Minimum[0]) ? (float) (string) $v : null,
            'Maximum' => (null !== $v = $xml->Maximum[0]) ? (float) (string) $v : null,
            'Unit' => (null !== $v = $xml->Unit[0]) ? (string) $v : null,
            'ExtendedStatistics' => (0 === ($v = $xml->ExtendedStatistics)->count()) ? null : $this->populateResultDatapointValueMap($v),
        ]);
    }

    /**
     * @return array<string, float>
     */
    private function populateResultDatapointValueMap(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->entry as $item) {
            $a = $item->value;
            $items[$item->key->__toString()] = (float) (string) $a;
        }

        return $items;
    }

    /**
     * @return Datapoint[]
     */
    private function populateResultDatapoints(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultDatapoint($item);
        }

        return $items;
    }
}
