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
     * ! Instead, you must reset the password with ResetServiceSpecificCredential.
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

        $this->serviceSpecificCredential = !$data->ServiceSpecificCredential ? null : new ServiceSpecificCredential([
            'CreateDate' => new \DateTimeImmutable((string) $data->ServiceSpecificCredential->CreateDate),
            'ServiceName' => (string) $data->ServiceSpecificCredential->ServiceName,
            'ServiceUserName' => (string) $data->ServiceSpecificCredential->ServiceUserName,
            'ServicePassword' => (string) $data->ServiceSpecificCredential->ServicePassword,
            'ServiceSpecificCredentialId' => (string) $data->ServiceSpecificCredential->ServiceSpecificCredentialId,
            'UserName' => (string) $data->ServiceSpecificCredential->UserName,
            'Status' => (string) $data->ServiceSpecificCredential->Status,
        ]);
    }
}
