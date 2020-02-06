<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetObjectAclOutput extends Result
{
    /**
     * Container for the bucket owner's display name and ID.
     */
    private $Owner;

    /**
     * A list of grants.
     */
    private $Grants = [];

    private $RequestCharged;

    /**
     * @return Grant[]
     */
    public function getGrants(): array
    {
        $this->initialize();

        return $this->Grants;
    }

    public function getOwner(): ?Owner
    {
        $this->initialize();

        return $this->Owner;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent(false));
        $this->Owner = new Owner([
            'DisplayName' => $this->xmlValueOrNull($data->Owner->DisplayName, 'string'),
            'ID' => $this->xmlValueOrNull($data->Owner->ID, 'string'),
        ]);
        $this->Grants = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new Grant([
                    'Grantee' => new Grantee([
                        'DisplayName' => $this->xmlValueOrNull($item->Grantee->DisplayName, 'string'),
                        'EmailAddress' => $this->xmlValueOrNull($item->Grantee->EmailAddress, 'string'),
                        'ID' => $this->xmlValueOrNull($item->Grantee->ID, 'string'),
                        'Type' => $this->xmlValueOrNull($item->Grantee['xsi:type'], 'string'),
                        'URI' => $this->xmlValueOrNull($item->Grantee->URI, 'string'),
                    ]),
                    'Permission' => $this->xmlValueOrNull($item->Permission, 'string'),
                ]);
            }

            return $items;
        })($data->AccessControlList);
    }
}
