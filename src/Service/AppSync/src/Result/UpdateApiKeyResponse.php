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

        $this->apiKey = empty($data['apiKey']) ? null : new ApiKey([
            'id' => isset($data['apiKey']['id']) ? (string) $data['apiKey']['id'] : null,
            'description' => isset($data['apiKey']['description']) ? (string) $data['apiKey']['description'] : null,
            'expires' => isset($data['apiKey']['expires']) ? (string) $data['apiKey']['expires'] : null,
            'deletes' => isset($data['apiKey']['deletes']) ? (string) $data['apiKey']['deletes'] : null,
        ]);
    }
}
