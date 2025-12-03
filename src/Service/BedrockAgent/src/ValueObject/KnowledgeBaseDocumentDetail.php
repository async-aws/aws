<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\DocumentStatus;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the details for a document that was ingested or deleted.
 */
final class KnowledgeBaseDocumentDetail
{
    /**
     * The identifier of the knowledge base that the document was ingested into or deleted from.
     *
     * @var string
     */
    private $knowledgeBaseId;

    /**
     * The identifier of the data source connected to the knowledge base that the document was ingested into or deleted
     * from.
     *
     * @var string
     */
    private $dataSourceId;

    /**
     * The ingestion status of the document. The following statuses are possible:
     *
     * - STARTING – You submitted the ingestion job containing the document.
     * - PENDING – The document is waiting to be ingested.
     * - IN_PROGRESS – The document is being ingested.
     * - INDEXED – The document was successfully indexed.
     * - PARTIALLY_INDEXED – The document was partially indexed.
     * - METADATA_PARTIALLY_INDEXED – You submitted metadata for an existing document and it was partially indexed.
     * - METADATA_UPDATE_FAILED – You submitted a metadata update for an existing document but it failed.
     * - FAILED – The document failed to be ingested.
     * - NOT_FOUND – The document wasn't found.
     * - IGNORED – The document was ignored during ingestion.
     * - DELETING – You submitted the delete job containing the document.
     * - DELETE_IN_PROGRESS – The document is being deleted.
     *
     * @var DocumentStatus::*
     */
    private $status;

    /**
     * Contains information that identifies the document.
     *
     * @var DocumentIdentifier
     */
    private $identifier;

    /**
     * The reason for the status. Appears alongside the status `IGNORED`.
     *
     * @var string|null
     */
    private $statusReason;

    /**
     * The date and time at which the document was last updated.
     *
     * @var \DateTimeImmutable|null
     */
    private $updatedAt;

    /**
     * @param array{
     *   knowledgeBaseId: string,
     *   dataSourceId: string,
     *   status: DocumentStatus::*,
     *   identifier: DocumentIdentifier|array,
     *   statusReason?: string|null,
     *   updatedAt?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->knowledgeBaseId = $input['knowledgeBaseId'] ?? $this->throwException(new InvalidArgument('Missing required field "knowledgeBaseId".'));
        $this->dataSourceId = $input['dataSourceId'] ?? $this->throwException(new InvalidArgument('Missing required field "dataSourceId".'));
        $this->status = $input['status'] ?? $this->throwException(new InvalidArgument('Missing required field "status".'));
        $this->identifier = isset($input['identifier']) ? DocumentIdentifier::create($input['identifier']) : $this->throwException(new InvalidArgument('Missing required field "identifier".'));
        $this->statusReason = $input['statusReason'] ?? null;
        $this->updatedAt = $input['updatedAt'] ?? null;
    }

    /**
     * @param array{
     *   knowledgeBaseId: string,
     *   dataSourceId: string,
     *   status: DocumentStatus::*,
     *   identifier: DocumentIdentifier|array,
     *   statusReason?: string|null,
     *   updatedAt?: \DateTimeImmutable|null,
     * }|KnowledgeBaseDocumentDetail $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSourceId(): string
    {
        return $this->dataSourceId;
    }

    public function getIdentifier(): DocumentIdentifier
    {
        return $this->identifier;
    }

    public function getKnowledgeBaseId(): string
    {
        return $this->knowledgeBaseId;
    }

    /**
     * @return DocumentStatus::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStatusReason(): ?string
    {
        return $this->statusReason;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
