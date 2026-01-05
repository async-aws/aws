<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\VectorBucket;

class GetVectorBucketOutput extends Result
{
    /**
     * The attributes of the vector bucket.
     *
     * @var VectorBucket
     */
    private $vectorBucket;

    public function getVectorBucket(): VectorBucket
    {
        $this->initialize();

        return $this->vectorBucket;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->vectorBucket = $this->populateResultVectorBucket($data['vectorBucket']);
    }

    private function populateResultEncryptionConfiguration(array $json): EncryptionConfiguration
    {
        return new EncryptionConfiguration([
            'sseType' => isset($json['sseType']) ? (string) $json['sseType'] : null,
            'kmsKeyArn' => isset($json['kmsKeyArn']) ? (string) $json['kmsKeyArn'] : null,
        ]);
    }

    private function populateResultVectorBucket(array $json): VectorBucket
    {
        return new VectorBucket([
            'vectorBucketName' => (string) $json['vectorBucketName'],
            'vectorBucketArn' => (string) $json['vectorBucketArn'],
            'creationTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['creationTime'])),
            'encryptionConfiguration' => empty($json['encryptionConfiguration']) ? null : $this->populateResultEncryptionConfiguration($json['encryptionConfiguration']),
        ]);
    }
}
