<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\Endpoint;

class DescribeEndpointsResponse extends Result
{
    /**
     * List of endpoints.
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
