<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\Enum\DataType;
use AsyncAws\S3Vectors\Enum\DistanceMetric;
use AsyncAws\S3Vectors\Enum\SseType;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\Index;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;

class GetIndexOutput extends Result
{
    /**
     * The attributes of the vector index.
     *
     * @var Index
     */
    private $index;

    public function getIndex(): Index
    {
        $this->initialize();

        return $this->index;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->index = $this->populateResultIndex($data['index']);
    }

    private function populateResultEncryptionConfiguration(array $json): EncryptionConfiguration
    {
        return new EncryptionConfiguration([
            'sseType' => isset($json['sseType']) ? (!SseType::exists((string) $json['sseType']) ? SseType::UNKNOWN_TO_SDK : (string) $json['sseType']) : null,
            'kmsKeyArn' => isset($json['kmsKeyArn']) ? (string) $json['kmsKeyArn'] : null,
        ]);
    }

    private function populateResultIndex(array $json): Index
    {
        return new Index([
            'vectorBucketName' => (string) $json['vectorBucketName'],
            'indexName' => (string) $json['indexName'],
            'indexArn' => (string) $json['indexArn'],
            'creationTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['creationTime'])),
            'dataType' => !DataType::exists((string) $json['dataType']) ? DataType::UNKNOWN_TO_SDK : (string) $json['dataType'],
            'dimension' => (int) $json['dimension'],
            'distanceMetric' => !DistanceMetric::exists((string) $json['distanceMetric']) ? DistanceMetric::UNKNOWN_TO_SDK : (string) $json['distanceMetric'],
            'metadataConfiguration' => empty($json['metadataConfiguration']) ? null : $this->populateResultMetadataConfiguration($json['metadataConfiguration']),
            'encryptionConfiguration' => empty($json['encryptionConfiguration']) ? null : $this->populateResultEncryptionConfiguration($json['encryptionConfiguration']),
        ]);
    }

    private function populateResultMetadataConfiguration(array $json): MetadataConfiguration
    {
        return new MetadataConfiguration([
            'nonFilterableMetadataKeys' => $this->populateResultNonFilterableMetadataKeys($json['nonFilterableMetadataKeys']),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultNonFilterableMetadataKeys(array $json): array
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
