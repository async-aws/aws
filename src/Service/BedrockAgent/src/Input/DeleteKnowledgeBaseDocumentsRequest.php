<?php

namespace AsyncAws\BedrockAgent\Input;

use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteKnowledgeBaseDocumentsRequest extends Input
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
     * A unique, case-sensitive identifier to ensure that the API request completes no more than one time. If this token
     * matches a previous request, Amazon Bedrock ignores the request, but does not return an error. For more information,
     * see Ensuring idempotency [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/Run_Instance_Idempotency.html
     *
     * @var string|null
     */
    private $clientToken;

    /**
     * A list of objects, each of which contains information to identify a document to delete.
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
     *   clientToken?: string|null,
     *   documentIdentifiers?: array<DocumentIdentifier|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->knowledgeBaseId = $input['knowledgeBaseId'] ?? null;
        $this->dataSourceId = $input['dataSourceId'] ?? null;
        $this->clientToken = $input['clientToken'] ?? null;
        $this->documentIdentifiers = isset($input['documentIdentifiers']) ? array_map([DocumentIdentifier::class, 'create'], $input['documentIdentifiers']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   clientToken?: string|null,
     *   documentIdentifiers?: array<DocumentIdentifier|array>,
     *   '@region'?: string|null,
     * }|DeleteKnowledgeBaseDocumentsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientToken(): ?string
    {
        return $this->clientToken;
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
        $uriString = '/knowledgebases/' . rawurlencode($uri['knowledgeBaseId']) . '/datasources/' . rawurlencode($uri['dataSourceId']) . '/documents/deleteDocuments';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientToken(?string $value): self
    {
        $this->clientToken = $value;

        return $this;
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

        if (null === $v = $this->clientToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['clientToken'] = $v;
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
