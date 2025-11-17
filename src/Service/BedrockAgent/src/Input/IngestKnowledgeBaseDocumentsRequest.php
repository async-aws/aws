<?php

namespace AsyncAws\BedrockAgent\Input;

use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocument;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class IngestKnowledgeBaseDocumentsRequest extends Input
{
    /**
     * The unique identifier of the knowledge base to ingest the documents into.
     *
     * @required
     *
     * @var string|null
     */
    private $knowledgeBaseId;

    /**
     * The unique identifier of the data source connected to the knowledge base that you're adding documents to.
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
     * A list of objects, each of which contains information about the documents to add.
     *
     * @required
     *
     * @var KnowledgeBaseDocument[]|null
     */
    private $documents;

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   clientToken?: string|null,
     *   documents?: array<KnowledgeBaseDocument|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->knowledgeBaseId = $input['knowledgeBaseId'] ?? null;
        $this->dataSourceId = $input['dataSourceId'] ?? null;
        $this->clientToken = $input['clientToken'] ?? null;
        $this->documents = isset($input['documents']) ? array_map([KnowledgeBaseDocument::class, 'create'], $input['documents']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   clientToken?: string|null,
     *   documents?: array<KnowledgeBaseDocument|array>,
     *   '@region'?: string|null,
     * }|IngestKnowledgeBaseDocumentsRequest $input
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
     * @return KnowledgeBaseDocument[]
     */
    public function getDocuments(): array
    {
        return $this->documents ?? [];
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
        $uriString = '/knowledgebases/' . rawurlencode($uri['knowledgeBaseId']) . '/datasources/' . rawurlencode($uri['dataSourceId']) . '/documents';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
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
     * @param KnowledgeBaseDocument[] $value
     */
    public function setDocuments(array $value): self
    {
        $this->documents = $value;

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
        if (null === $v = $this->documents) {
            throw new InvalidArgument(\sprintf('Missing parameter "documents" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['documents'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['documents'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
