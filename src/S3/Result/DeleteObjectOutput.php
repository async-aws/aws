<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DeleteObjectOutput extends Result
{
    /**
     * Specifies whether the versioned object that was permanently deleted was (true) or was not (false) a delete marker.
     */
    private $DeleteMarker;

    /**
     * Returns the version ID of the delete marker created as a result of the DELETE operation.
     */
    private $VersionId;

    private $RequestCharged;

    public function getDeleteMarker(): ?bool
    {
        $this->initialize();

        return $this->DeleteMarker;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->DeleteMarker = $headers['x-amz-delete-marker'];
        $this->VersionId = $headers['x-amz-version-id'];
        $this->RequestCharged = $headers['x-amz-request-charged'];
    }
}
