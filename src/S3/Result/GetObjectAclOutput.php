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

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent(false));
        $this->Owner = new Owner([
            'DisplayName' => ($v = $data->Owner->DisplayName) ? (string) $v : null,
            'ID' => ($v = $data->Owner->ID) ? (string) $v : null,
        ]);
        $this->Grants = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml->Grant as $item) {
                $items[] = new Grant([
                    'Grantee' => new Grantee([
                        'DisplayName' => ($v = $item->Grantee->DisplayName) ? (string) $v : null,
                        'EmailAddress' => ($v = $item->Grantee->EmailAddress) ? (string) $v : null,
                        'ID' => ($v = $item->Grantee->ID) ? (string) $v : null,
                        'Type' => ($v = $item->Grantee->attributes('xsi', true)['type'][0] ?? null) ? (string) $v : null,
                        'URI' => ($v = $item->Grantee->URI) ? (string) $v : null,
                    ]),
                    'Permission' => ($v = $item->Permission) ? (string) $v : null,
                ]);
            }

            return $items;
        })($data->AccessControlList);
    }
}
