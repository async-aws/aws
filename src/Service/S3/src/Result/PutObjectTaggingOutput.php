<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutObjectTaggingOutput extends Result
{
    /**
     * The versionId of the object the tag-set was added to.
     *
     * @var string|null
     */
    private $versionId;

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->versionId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
    }
}
