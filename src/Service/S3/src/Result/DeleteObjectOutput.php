<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;

class DeleteObjectOutput extends Result
{
    /**
     * Indicates whether the specified object version that was permanently deleted was (true) or was not (false) a delete
     * marker before deletion. In a simple DELETE, this header indicates whether (true) or not (false) the current version
     * of the object is a delete marker.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var bool|null
     */
    private $deleteMarker;

    /**
     * Returns the version ID of the delete marker created as a result of the DELETE operation.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    public function getDeleteMarker(): ?bool
    {
        $this->initialize();

        return $this->deleteMarker;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->requestCharged;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->versionId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->deleteMarker = isset($headers['x-amz-delete-marker'][0]) ? filter_var($headers['x-amz-delete-marker'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;
    }
}
