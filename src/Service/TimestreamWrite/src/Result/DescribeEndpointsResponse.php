<?php

namespace AsyncAws\TimestreamWrite\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\TimestreamWrite\ValueObject\Endpoint;

class DescribeEndpointsResponse extends Result
{
    /**
     * An `Endpoints` object is returned when a `DescribeEndpoints` request is made.
     */
    private $endpoints;

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array
    {
        $this->initialize();

        return $this->endpoints;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->endpoints = $this->populateResultEndpoints($data['Endpoints']);
    }

    private function populateResultEndpoint(array $json): Endpoint
    {
        return new Endpoint([
            'Address' => (string) $json['Address'],
            'CachePeriodInMinutes' => (string) $json['CachePeriodInMinutes'],
        ]);
    }

    /**
     * @return Endpoint[]
     */
    private function populateResultEndpoints(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEndpoint($item);
        }

        return $items;
    }
}
