<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateIndexOutput extends Result
{
    /**
     * The Amazon Resource Name (ARN) of the newly created vector index.
     *
     * @var string
     */
    private $indexArn;

    public function getIndexArn(): string
    {
        $this->initialize();

        return $this->indexArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->indexArn = (string) $data['indexArn'];
    }
}
