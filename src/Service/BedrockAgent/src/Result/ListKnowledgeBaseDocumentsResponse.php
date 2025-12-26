<?php

namespace AsyncAws\BedrockAgent\Result;

use AsyncAws\BedrockAgent\BedrockAgentClient;
use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\BedrockAgent\Enum\DocumentStatus;
use AsyncAws\BedrockAgent\Input\ListKnowledgeBaseDocumentsRequest;
use AsyncAws\BedrockAgent\ValueObject\CustomDocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\DocumentIdentifier;
use AsyncAws\BedrockAgent\ValueObject\KnowledgeBaseDocumentDetail;
use AsyncAws\BedrockAgent\ValueObject\S3Location;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<KnowledgeBaseDocumentDetail>
 */
class ListKnowledgeBaseDocumentsResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of objects, each of which contains information about the documents that were retrieved.
     *
     * @var KnowledgeBaseDocumentDetail[]
     */
    private $documentDetails;

    /**
     * If the total number of results is greater than the `maxResults` value provided in the request, use this token when
     * making another request in the `nextToken` field to return the next batch of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<KnowledgeBaseDocumentDetail>
     */
    public function getDocumentDetails(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->documentDetails;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof BedrockAgentClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListKnowledgeBaseDocumentsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listKnowledgeBaseDocuments($input));
            } else {
                $nextPage = null;
            }

            yield from $page->documentDetails;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over documentDetails.
     *
     * @return \Traversable<KnowledgeBaseDocumentDetail>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getDocumentDetails();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->documentDetails = $this->populateResultKnowledgeBaseDocumentDetails($data['documentDetails'] ?? []);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
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
