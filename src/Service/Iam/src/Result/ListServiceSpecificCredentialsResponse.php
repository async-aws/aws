<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\ValueObject\ServiceSpecificCredentialMetadata;

class ListServiceSpecificCredentialsResponse extends Result
{
    /**
     * A list of structures that each contain details about a service-specific credential.
     *
     * @var ServiceSpecificCredentialMetadata[]
     */
    private $serviceSpecificCredentials;

    /**
     * @return ServiceSpecificCredentialMetadata[]
     */
    public function getServiceSpecificCredentials(): array
    {
        $this->initialize();

        return $this->serviceSpecificCredentials;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListServiceSpecificCredentialsResult;

        $this->serviceSpecificCredentials = (0 === ($v = $data->ServiceSpecificCredentials)->count()) ? [] : $this->populateResultServiceSpecificCredentialsListType($v);
    }

    private function populateResultServiceSpecificCredentialMetadata(\SimpleXMLElement $xml): ServiceSpecificCredentialMetadata
    {
        return new ServiceSpecificCredentialMetadata([
            'UserName' => (string) $xml->UserName,
            'Status' => (string) $xml->Status,
            'ServiceUserName' => (string) $xml->ServiceUserName,
            'CreateDate' => new \DateTimeImmutable((string) $xml->CreateDate),
            'ServiceSpecificCredentialId' => (string) $xml->ServiceSpecificCredentialId,
            'ServiceName' => (string) $xml->ServiceName,
        ]);
    }

    /**
     * @return ServiceSpecificCredentialMetadata[]
     */
    private function populateResultServiceSpecificCredentialsListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultServiceSpecificCredentialMetadata($item);
        }

        return $items;
    }
}
