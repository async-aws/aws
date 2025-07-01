<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\ServiceSpecificCredential;

class CreateServiceSpecificCredentialResponse extends Result
{
    /**
     * A structure that contains information about the newly created service-specific credential.
     *
     * ! This is the only time that the password for this credential set is available. It cannot be recovered later.
     * ! Instead, you must reset the password with ResetServiceSpecificCredential [^1].
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_ResetServiceSpecificCredential.html
     *
     * @var ServiceSpecificCredential|null
     */
    private $serviceSpecificCredential;

    public function getServiceSpecificCredential(): ?ServiceSpecificCredential
    {
        $this->initialize();

        return $this->serviceSpecificCredential;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreateServiceSpecificCredentialResult;

        $this->serviceSpecificCredential = 0 === $data->ServiceSpecificCredential->count() ? null : $this->populateResultServiceSpecificCredential($data->ServiceSpecificCredential);
    }

    private function populateResultServiceSpecificCredential(\SimpleXMLElement $xml): ServiceSpecificCredential
    {
        return new ServiceSpecificCredential([
            'CreateDate' => new \DateTimeImmutable((string) $xml->CreateDate),
            'ExpirationDate' => (null !== $v = $xml->ExpirationDate[0]) ? new \DateTimeImmutable((string) $v) : null,
            'ServiceName' => (string) $xml->ServiceName,
            'ServiceUserName' => (null !== $v = $xml->ServiceUserName[0]) ? (string) $v : null,
            'ServicePassword' => (null !== $v = $xml->ServicePassword[0]) ? (string) $v : null,
            'ServiceCredentialAlias' => (null !== $v = $xml->ServiceCredentialAlias[0]) ? (string) $v : null,
            'ServiceCredentialSecret' => (null !== $v = $xml->ServiceCredentialSecret[0]) ? (string) $v : null,
            'ServiceSpecificCredentialId' => (string) $xml->ServiceSpecificCredentialId,
            'UserName' => (string) $xml->UserName,
            'Status' => (string) $xml->Status,
        ]);
    }
}
