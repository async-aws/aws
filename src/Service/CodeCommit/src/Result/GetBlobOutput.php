<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a get blob operation.
 */
class GetBlobOutput extends Result
{
    /**
     * The content of the blob, usually a file.
     */
    private $content;

    public function getContent(): string
    {
        $this->initialize();

        return $this->content;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->content = base64_decode((string) $data['content']);
    }
}
