<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\ValueObject\ServerSideEncryptionByDefault;
use AsyncAws\S3\ValueObject\ServerSideEncryptionConfiguration;
use AsyncAws\S3\ValueObject\ServerSideEncryptionRule;

class GetBucketEncryptionOutput extends Result
{
    /**
     * @var ServerSideEncryptionConfiguration|null
     */
    private $serverSideEncryptionConfiguration;

    public function getServerSideEncryptionConfiguration(): ?ServerSideEncryptionConfiguration
    {
        $this->initialize();

        return $this->serverSideEncryptionConfiguration;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->serverSideEncryptionConfiguration = $this->populateResultServerSideEncryptionConfiguration($data);
    }

    private function populateResultServerSideEncryptionByDefault(\SimpleXMLElement $xml): ServerSideEncryptionByDefault
    {
        return new ServerSideEncryptionByDefault([
            'SSEAlgorithm' => (string) $xml->SSEAlgorithm,
            'KMSMasterKeyID' => (null !== $v = $xml->KMSMasterKeyID[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultServerSideEncryptionConfiguration(\SimpleXMLElement $xml): ServerSideEncryptionConfiguration
    {
        return new ServerSideEncryptionConfiguration([
            'Rules' => $this->populateResultServerSideEncryptionRules($xml->Rule),
        ]);
    }

    private function populateResultServerSideEncryptionRule(\SimpleXMLElement $xml): ServerSideEncryptionRule
    {
        return new ServerSideEncryptionRule([
            'ApplyServerSideEncryptionByDefault' => 0 === $xml->ApplyServerSideEncryptionByDefault->count() ? null : $this->populateResultServerSideEncryptionByDefault($xml->ApplyServerSideEncryptionByDefault),
            'BucketKeyEnabled' => (null !== $v = $xml->BucketKeyEnabled[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return ServerSideEncryptionRule[]
     */
    private function populateResultServerSideEncryptionRules(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultServerSideEncryptionRule($item);
        }

        return $items;
    }
}
