<?php

namespace AsyncAws\BedrockAgent\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListKnowledgeBaseDocumentsRequest extends Input
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
     * The maximum number of results to return in the response. If the total number of results is greater than this value,
     * use the token returned in the response in the `nextToken` field when making another request to return the next batch
     * of results.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * If the total number of results is greater than the `maxResults` value provided in the request, enter the token
     * returned in the `nextToken` field in the response in this field to return the next batch of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->knowledgeBaseId = $input['knowledgeBaseId'] ?? null;
        $this->dataSourceId = $input['dataSourceId'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   knowledgeBaseId?: string,
     *   dataSourceId?: string,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     * }|ListKnowledgeBaseDocumentsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSourceId(): ?string
    {
        return $this->dataSourceId;
    }

    public function getKnowledgeBaseId(): ?string
    {
        return $this->knowledgeBaseId;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
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
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDataSourceId(?string $value): self
    {
        $this->dataSourceId = $value;

        return $this;
    }

    public function setKnowledgeBaseId(?string $value): self
    {
        $this->knowledgeBaseId = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->maxResults) {
            $payload['maxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }

        return $payload;
    }
}
