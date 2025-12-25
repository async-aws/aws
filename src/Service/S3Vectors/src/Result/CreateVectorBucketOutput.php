<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateVectorBucketOutput extends Result
{
    /**
     * The Amazon Resource Name (ARN) of the newly created vector bucket.
     *
     * @var string
     */
    private $vectorBucketArn;

    public function getVectorBucketArn(): string
    {
        $this->initialize();

        return $this->vectorBucketArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->vectorBucketArn = (string) $data['vectorBucketArn'];
    }
}
