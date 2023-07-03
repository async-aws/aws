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

        $this->serviceSpecificCredentials = !$data->ServiceSpecificCredentials ? [] : $this->populateResultServiceSpecificCredentialsListType($data->ServiceSpecificCredentials);
    }

    /**
     * @return ServiceSpecificCredentialMetadata[]
     */
    private function populateResultServiceSpecificCredentialsListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new ServiceSpecificCredentialMetadata([
                'UserName' => (string) $item->UserName,
                'Status' => (string) $item->Status,
                'ServiceUserName' => (string) $item->ServiceUserName,
                'CreateDate' => new \DateTimeImmutable((string) $item->CreateDate),
                'ServiceSpecificCredentialId' => (string) $item->ServiceSpecificCredentialId,
                'ServiceName' => (string) $item->ServiceName,
            ]);
        }

        return $items;
    }
}
