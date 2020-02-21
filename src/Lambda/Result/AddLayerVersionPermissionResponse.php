<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AddLayerVersionPermissionResponse extends Result
{
    /**
     * The permission statement.
     */
    private $Statement;

    /**
     * A unique identifier for the current revision of the policy.
     */
    private $RevisionId;

    public function getRevisionId(): ?string
    {
        $this->initialize();

        return $this->RevisionId;
    }

    public function getStatement(): ?string
    {
        $this->initialize();

        return $this->Statement;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = json_decode($response->getContent(false), true);

        $this->Statement = ($v = $data['Statement']) ? (string) $v : null;
        $this->RevisionId = ($v = $data['RevisionId']) ? (string) $v : null;
    }
}
