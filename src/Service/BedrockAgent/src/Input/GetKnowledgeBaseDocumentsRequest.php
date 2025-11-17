<?php

namespace AsyncAws\BedrockAgent\Input;

use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetKnowledgeBaseDocumentsRequest extends Input
{
    /**
     * The unique identifier of the knowledge base that is connected to the data source.
     *
     * @required
     *
     * @var string|null
     */
    private $knowledgeBaseId;

    /**
     * The unique identifier of the data source that contains the documents.
     *
     * @required
     *
     * @var string|null
     */
    private $dataSourceId;

    /**
     * A list of objects, each of which contains information to identify a document for which to retrieve information.
     *
     * @required
     *
     * @var DocumentIdentifier[]|null
     */
    private $documentIdentifiers;

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   documentIdentifiers?: array<DocumentIdentifier|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->knowledgeBaseId = $input['knowledgeBaseId'] ?? null;
        $this->dataSourceId = $input['dataSourceId'] ?? null;
        $this->documentIdentifiers = isset($input['documentIdentifiers']) ? array_map([DocumentIdentifier::class, 'create'], $input['documentIdentifiers']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   documentIdentifiers?: array<DocumentIdentifier|array>,
     *   '@region'?: string|null,
     * }|GetKnowledgeBaseDocumentsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSourceId(): ?string
    {
        return $this->dataSourceId;
    }

    /**
     * @return DocumentIdentifier[]
     */
    public function getDocumentIdentifiers(): array
    {
        return $this->documentIdentifiers ?? [];
    }

    public function getKnowledgeBaseId(): ?string
    {
        return $this->knowledgeBaseId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->knowledgeBaseId) {
            throw new InvalidArgument(\sprintf('Missing parameter "knowledgeBaseId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['knowledgeBaseId'] = $v;
        if (null === $v = $this->dataSourceId) {
            throw new InvalidArgument(\sprintf('Missing parameter "dataSourceId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['dataSourceId'] = $v;
        $uriString = '/knowledgebases/' . rawurlencode($uri['knowledgeBaseId']) . '/datasources/' . rawurlencode($uri['dataSourceId']) . '/documents/getDocuments';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDataSourceId(?string $value): self
    {
        $this->dataSourceId = $value;

        return $this;
    }

    /**
     * @param DocumentIdentifier[] $value
     */
    public function setDocumentIdentifiers(array $value): self
    {
        $this->documentIdentifiers = $value;

        return $this;
    }

    public function setKnowledgeBaseId(?string $value): self
    {
        $this->knowledgeBaseId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->documentIdentifiers) {
            throw new InvalidArgument(\sprintf('Missing parameter "documentIdentifiers" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['documentIdentifiers'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['documentIdentifiers'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
