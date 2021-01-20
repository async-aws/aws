<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\AccessKey;

/**
 * Contains the response to a successful CreateAccessKey request.
 */
class CreateAccessKeyResponse extends Result
{
    /**
     * A structure with details about the access key.
     */
    private $accessKey;

    public function getAccessKey(): AccessKey
    {
        $this->initialize();

        return $this->accessKey;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateAccessKeyResult;

        $this->accessKey = new AccessKey([
            'UserName' => (string) $data->AccessKey->UserName,
            'AccessKeyId' => (string) $data->AccessKey->AccessKeyId,
            'Status' => (string) $data->AccessKey->Status,
            'SecretAccessKey' => (string) $data->AccessKey->SecretAccessKey,
            'CreateDate' => ($v = $data->AccessKey->CreateDate) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }
}
