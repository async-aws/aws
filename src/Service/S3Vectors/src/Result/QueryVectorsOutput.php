<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\Enum\DistanceMetric;
use AsyncAws\S3Vectors\ValueObject\QueryOutputVector;

class QueryVectorsOutput extends Result
{
    /**
     * The vectors in the approximate nearest neighbor search.
     *
     * @var QueryOutputVector[]
     */
    private $vectors;

    /**
     * The distance metric that was used for the similarity search calculation. This is the same distance metric that was
     * configured for the vector index when it was created.
     *
     * @var DistanceMetric::*
     */
    private $distanceMetric;

    /**
     * @return DistanceMetric::*
     */
    public function getDistanceMetric(): string
    {
        $this->initialize();

        return $this->distanceMetric;
    }

    /**
     * @return QueryOutputVector[]
     */
    public function getVectors(): array
    {
        $this->initialize();

        return $this->vectors;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->vectors = $this->populateResultQueryVectorsOutputList($data['vectors'] ?? []);
        $this->distanceMetric = !DistanceMetric::exists((string) $data['distanceMetric']) ? DistanceMetric::UNKNOWN_TO_SDK : (string) $data['distanceMetric'];
    }

    private function populateResultQueryOutputVector(array $json): QueryOutputVector
    {
        return new QueryOutputVector([
            'distance' => isset($json['distance']) ? (float) $json['distance'] : null,
            'key' => (string) $json['key'],
            'metadata' => $json['metadata'] ?? null,
        ]);
    }

    /**
     * @return QueryOutputVector[]
     */
    private function populateResultQueryVectorsOutputList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultQueryOutputVector($item);
        }

        return $items;
    }
}
