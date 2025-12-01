<?php

namespace AsyncAws\BedrockAgent\Result;

use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Enum\DocumentStatus;
use AsyncAws\BedrockAgent\ValueObject\CustomDocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocumentDetail;
use AsyncAws\BedrockAgent\ValueObject\S3Location;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteKnowledgeBaseDocumentsResponse extends Result
{
    /**
     * A list of objects, each of which contains information about the documents that were deleted.
     *
     * @var KnowledgeBaseDocumentDetail[]
     */
    private $documentDetails;

    /**
     * @return KnowledgeBaseDocumentDetail[]
     */
    public function getDocumentDetails(): array
    {
        $this->initialize();

        return $this->documentDetails;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->documentDetails = empty($data['documentDetails']) ? [] : $this->populateResultKnowledgeBaseDocumentDetails($data['documentDetails']);
    }

    private function populateResultCustomDocumentIdentifier(array $json): CustomDocumentIdentifier
    {
        return new CustomDocumentIdentifier([
            'id' => (string) $json['id'],
        ]);
    }

    private function populateResultDocumentIdentifier(array $json): DocumentIdentifier
    {
        return new DocumentIdentifier([
            'dataSourceType' => !ContentDataSourceType::exists((string) $json['dataSourceType']) ? ContentDataSourceType::UNKNOWN_TO_SDK : (string) $json['dataSourceType'],
            's3' => empty($json['s3']) ? null : $this->populateResultS3Location($json['s3']),
            'custom' => empty($json['custom']) ? null : $this->populateResultCustomDocumentIdentifier($json['custom']),
        ]);
    }

    private function populateResultKnowledgeBaseDocumentDetail(array $json): KnowledgeBaseDocumentDetail
    {
        return new KnowledgeBaseDocumentDetail([
            'knowledgeBaseId' => (string) $json['knowledgeBaseId'],
            'dataSourceId' => (string) $json['dataSourceId'],
            'status' => !DocumentStatus::exists((string) $json['status']) ? DocumentStatus::UNKNOWN_TO_SDK : (string) $json['status'],
            'identifier' => $this->populateResultDocumentIdentifier($json['identifier']),
            'statusReason' => isset($json['statusReason']) ? (string) $json['statusReason'] : null,
            'updatedAt' => isset($json['updatedAt']) && ($d = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $json['updatedAt'])) ? $d : null,
        ]);
    }

    /**
     * @return KnowledgeBaseDocumentDetail[]
     */
    private function populateResultKnowledgeBaseDocumentDetails(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultKnowledgeBaseDocumentDetail($item);
        }

        return $items;
    }

    private function populateResultS3Location(array $json): S3Location
    {
        return new S3Location([
            'uri' => (string) $json['uri'],
        ]);
    }
}
