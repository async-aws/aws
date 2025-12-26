<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetVectorBucketPolicyOutput extends Result
{
    /**
     * The `JSON` that defines the policy.
     *
     * @var string|null
     */
    private $policy;

    public function getPolicy(): ?string
    {
        $this->initialize();

        return $this->policy;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->policy = isset($data['policy']) ? (string) $data['policy'] : null;
    }
}
