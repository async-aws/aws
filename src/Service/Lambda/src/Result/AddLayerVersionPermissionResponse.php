<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AddLayerVersionPermissionResponse extends Result
{
    private $Statement;

    private $RevisionId;

    /**
     * A unique identifier for the current revision of the policy.
     */
    public function getRevisionId(): ?string
    {
        $this->initialize();

        return $this->RevisionId;
    }

    /**
     * The permission statement.
     */
    public function getStatement(): ?string
    {
        $this->initialize();

        return $this->Statement;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = $response->toArray(false);

        $this->Statement = isset($data['Statement']) ? (string) $data['Statement'] : null;
        $this->RevisionId = isset($data['RevisionId']) ? (string) $data['RevisionId'] : null;
    }
}
