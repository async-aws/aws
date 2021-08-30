<?php

namespace AsyncAws\CloudWatch\Result;

use AsyncAws\CloudWatch\ValueObject\Datapoint;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetMetricStatisticsOutput extends Result
{
    /**
     * A label for the specified metric.
     */
    private $label;

    /**
     * The data points for the specified metric.
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

        $this->label = ($v = $data->Label) ? (string) $v : null;
        $this->datapoints = !$data->Datapoints ? [] : $this->populateResultDatapoints($data->Datapoints);
    }

    /**
     * @return array<string, float>
     */
    private function populateResultDatapointValueMap(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->entry as $item) {
            if (null === $a = $item->value) {
                continue;
            }
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
            $items[] = new Datapoint([
                'Timestamp' => ($v = $item->Timestamp) ? new \DateTimeImmutable((string) $v) : null,
                'SampleCount' => ($v = $item->SampleCount) ? (float) (string) $v : null,
                'Average' => ($v = $item->Average) ? (float) (string) $v : null,
                'Sum' => ($v = $item->Sum) ? (float) (string) $v : null,
                'Minimum' => ($v = $item->Minimum) ? (float) (string) $v : null,
                'Maximum' => ($v = $item->Maximum) ? (float) (string) $v : null,
                'Unit' => ($v = $item->Unit) ? (string) $v : null,
                'ExtendedStatistics' => !$item->ExtendedStatistics ? [] : $this->populateResultDatapointValueMap($item->ExtendedStatistics),
            ]);
        }

        return $items;
    }
}
