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
     * When IsTruncated is true, this element is present and contains the value to use for the Marker parameter in a
     * subsequent pagination request.
     *
     * @var string|null
     */
    private $marker;

    /**
     * A flag that indicates whether there are more items to return. If your results were truncated, you can make a
     * subsequent pagination request using the Marker request parameter to retrieve more items.
     *
     * @var bool|null
     */
    private $isTruncated;

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    public function getMarker(): ?string
    {
        $this->initialize();

        return $this->marker;
    }

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
        $this->marker = (null !== $v = $data->Marker[0]) ? (string) $v : null;
        $this->isTruncated = (null !== $v = $data->IsTruncated[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
    }

    private function populateResultServiceSpecificCredentialMetadata(\SimpleXMLElement $xml): ServiceSpecificCredentialMetadata
    {
        return new ServiceSpecificCredentialMetadata([
            'UserName' => (string) $xml->UserName,
            'Status' => (string) $xml->Status,
            'ServiceUserName' => (null !== $v = $xml->ServiceUserName[0]) ? (string) $v : null,
            'ServiceCredentialAlias' => (null !== $v = $xml->ServiceCredentialAlias[0]) ? (string) $v : null,
            'CreateDate' => new \DateTimeImmutable((string) $xml->CreateDate),
            'ExpirationDate' => (null !== $v = $xml->ExpirationDate[0]) ? new \DateTimeImmutable((string) $v) : null,
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
