<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetObjectAclOutput extends Result
{
    private $Owner;

    private $Grants = [];

    private $RequestCharged;

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'];

        $data = new \SimpleXMLElement($response->getContent(false));
        $this->Owner = new Owner([
        'DisplayName' => $this->xmlValueOrNull($data->DisplayName, 'string'),
        'ID' => $this->xmlValueOrNull($data->ID, 'string'),
        ]);
        $this->Grants = [];
        foreach ($data->Grants as $item) {
            $this->Grants[] = new Grant([
        'Grantee' => new Grantee([
        'DisplayName' => $this->xmlValueOrNull($item->Grantee->DisplayName, 'string'),
        'EmailAddress' => $this->xmlValueOrNull($item->Grantee->EmailAddress, 'string'),
        'ID' => $this->xmlValueOrNull($item->Grantee->ID, 'string'),
        'Type' => $this->xmlValueOrNull($item->Grantee->Type, 'string'),
        'URI' => $this->xmlValueOrNull($item->Grantee->URI, 'string'),
        ]),
        'Permission' => $this->xmlValueOrNull($item->Permission, 'string'),
        ]);
        }
    }

    public function getOwner(): ?Owner
    {
        $this->initialize();

        return $this->Owner;
    }

    /**
     * @return Grant[]
     */
    public function getGrants(): array
    {
        $this->initialize();

        return $this->Grants;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }
}
