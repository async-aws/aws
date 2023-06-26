<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\ValueObject\ApiKey;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class ListApiKeysResponse extends Result
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
     * @return ApiKey[]
     */
    public function getApiKeys(): array
    {
        $this->initialize();

        return $this->apiKeys;
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
