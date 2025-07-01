<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\AccessKey;

/**
 * Contains the response to a successful CreateAccessKey [^1] request.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateAccessKey.html
 */
class CreateAccessKeyResponse extends Result
{
    /**
     * A structure with details about the access key.
     *
     * @var AccessKey
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

        $this->accessKey = $this->populateResultAccessKey($data->AccessKey);
    }

    private function populateResultAccessKey(\SimpleXMLElement $xml): AccessKey
    {
        return new AccessKey([
            'UserName' => (string) $xml->UserName,
            'AccessKeyId' => (string) $xml->AccessKeyId,
            'Status' => (string) $xml->Status,
            'SecretAccessKey' => (string) $xml->SecretAccessKey,
            'CreateDate' => (null !== $v = $xml->CreateDate[0]) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }
}
