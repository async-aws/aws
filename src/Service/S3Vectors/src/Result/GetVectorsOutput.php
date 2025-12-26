<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\ValueObject\GetOutputVector;
use AsyncAws\S3Vectors\ValueObject\VectorData;
use AsyncAws\S3Vectors\ValueObject\VectorDataMemberFloat32;
use AsyncAws\S3Vectors\ValueObject\VectorDataMemberUnknownToSdk;

class GetVectorsOutput extends Result
{
    /**
     * The attributes of the vectors.
     *
     * @var GetOutputVector[]
     */
    private $vectors;

    /**
     * @return GetOutputVector[]
     */
    public function getVectors(): array
    {
        $this->initialize();

        return $this->vectors;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->vectors = $this->populateResultGetVectorsOutputList($data['vectors'] ?? []);
    }

    /**
     * @return float[]
     */
    private function populateResultFloat32VectorData(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultGetOutputVector(array $json): GetOutputVector
    {
        return new GetOutputVector([
            'key' => (string) $json['key'],
            'data' => empty($json['data']) ? null : $this->populateResultVectorData($json['data']),
            'metadata' => $json['metadata'] ?? null,
        ]);
    }

    /**
     * @return GetOutputVector[]
     */
    private function populateResultGetVectorsOutputList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultGetOutputVector($item);
        }

        return $items;
    }

    private function populateResultVectorData(array $json): VectorData
    {
        if (isset($json['float32'])) {
            return $this->populateResultVectorDataMemberFloat32($json);
        }

        return $this->populateResultVectorDataMemberUnknownToSdk($json);
    }

    private function populateResultVectorDataMemberFloat32(array $json): VectorDataMemberFloat32
    {
        return new VectorDataMemberFloat32([
            'float32' => $this->populateResultFloat32VectorData($json['float32']),
        ]);
    }

    private function populateResultVectorDataMemberUnknownToSdk(array $json): VectorData
    {
        return new VectorDataMemberUnknownToSdk($json);
    }
}
