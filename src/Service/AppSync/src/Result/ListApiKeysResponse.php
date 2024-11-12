<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\AppSyncClient;
use AsyncAws\AppSync\Input\ListApiKeysRequest;
use AsyncAws\AppSync\ValueObject\ApiKey;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<ApiKey>
 */
class ListApiKeysResponse extends Result implements \IteratorAggregate
{
    /**
     * The `ApiKey` objects.
     *
     * @var ApiKey[]
     */
    private $apiKeys;

    /**
     * An identifier to pass in the next request to this operation to return the next set of items in the list.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ApiKey>
     */
    public function getApiKeys(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->apiKeys;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof AppSyncClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListApiKeysRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listApiKeys($input));
            } else {
                $nextPage = null;
            }

            yield from $page->apiKeys;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over apiKeys.
     *
     * @return \Traversable<ApiKey>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getApiKeys();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->apiKeys = empty($data['apiKeys']) ? [] : $this->populateResultApiKeys($data['apiKeys']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultApiKey(array $json): ApiKey
    {
        return new ApiKey([
            'id' => isset($json['id']) ? (string) $json['id'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'expires' => isset($json['expires']) ? (int) $json['expires'] : null,
            'deletes' => isset($json['deletes']) ? (int) $json['deletes'] : null,
        ]);
    }

    /**
     * @return ApiKey[]
     */
    private function populateResultApiKeys(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultApiKey($item);
        }

        return $items;
    }
}
