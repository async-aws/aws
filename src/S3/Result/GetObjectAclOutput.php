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
            'DisplayName' => static::xmlValueOrNull($data->Owner->DisplayName, 'string'),
            'ID' => static::xmlValueOrNull($data->Owner->ID, 'string'),
        ]);
        $this->Grants = (static function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml->Grant as $item) {
                $items[] = new Grant([
                    'Grantee' => new Grantee([
                        'DisplayName' => static::xmlValueOrNull($item->Grantee->DisplayName, 'string'),
                        'EmailAddress' => static::xmlValueOrNull($item->Grantee->EmailAddress, 'string'),
                        'ID' => static::xmlValueOrNull($item->Grantee->ID, 'string'),
                        'Type' => static::xmlValueOrNull($item->Grantee->attributes('xsi', true)['type'][0] ?? null, 'string'),
                        'URI' => static::xmlValueOrNull($item->Grantee->URI, 'string'),
                    ]),
                    'Permission' => static::xmlValueOrNull($item->Permission, 'string'),
                ]);
            }

            return $items;
        })($data->AccessControlList);
    }
}
