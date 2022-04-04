<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\ValueObject\ApiKey;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateApiKeyResponse extends Result
{
    /**
     * The API key.
     */
    private $apiKey;

    public function getApiKey(): ?ApiKey
    {
        $this->initialize();

        return $this->apiKey;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->apiKey = empty($data['apiKey']) ? null : $this->populateResultApiKey($data['apiKey']);
    }

    private function populateResultApiKey(array $json): ApiKey
    {
        return new ApiKey([
            'id' => isset($json['id']) ? (string) $json['id'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'expires' => isset($json['expires']) ? (string) $json['expires'] : null,
            'deletes' => isset($json['deletes']) ? (string) $json['deletes'] : null,
        ]);
    }
}
