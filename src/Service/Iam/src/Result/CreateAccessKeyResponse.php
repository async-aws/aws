<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\AccessKey;

class CreateAccessKeyResponse extends Result
{
    /**
     * A structure with details about the access key.
     */
    private $AccessKey;

    public function getAccessKey(): AccessKey
    {
        $this->initialize();

        return $this->AccessKey;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateAccessKeyResult;

        $this->AccessKey = new AccessKey([
            'UserName' => (string) $data->AccessKey->UserName,
            'AccessKeyId' => (string) $data->AccessKey->AccessKeyId,
            'Status' => (string) $data->AccessKey->Status,
            'SecretAccessKey' => (string) $data->AccessKey->SecretAccessKey,
            'CreateDate' => ($v = $data->AccessKey->CreateDate) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }
}
