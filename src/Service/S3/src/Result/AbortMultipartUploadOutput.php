<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;

class AbortMultipartUploadOutput extends Result
{
    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    /**
     * Version ID of the object.
     *
     * Returns the Version ID of the object for the aborted multipart upload, if bucket versioning is enabled.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

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

        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;
        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
    }
}
