<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\ValueObject\Grant;
use AsyncAws\S3\ValueObject\Grantee;
use AsyncAws\S3\ValueObject\Owner;

class GetObjectAclOutput extends Result
{
    /**
     * Container for the bucket owner's display name and ID.
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * A list of grants.
     *
     * @var Grant[]
     */
    private $grants;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    /**
     * @return Grant[]
     */
    public function getGrants(): array
    {
        $this->initialize();

        return $this->grants;
    }

    public function getOwner(): ?Owner
    {
        $this->initialize();

        return $this->owner;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->requestCharged;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->owner = 0 === $data->Owner->count() ? null : $this->populateResultOwner($data->Owner);
        $this->grants = (0 === ($v = $data->AccessControlList)->count()) ? [] : $this->populateResultGrants($v);
    }

    private function populateResultGrant(\SimpleXMLElement $xml): Grant
    {
        return new Grant([
            'Grantee' => 0 === $xml->Grantee->count() ? null : $this->populateResultGrantee($xml->Grantee),
            'Permission' => (null !== $v = $xml->Permission[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultGrantee(\SimpleXMLElement $xml): Grantee
    {
        return new Grantee([
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
            'EmailAddress' => (null !== $v = $xml->EmailAddress[0]) ? (string) $v : null,
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
            'Type' => (string) ($xml->attributes('xsi', true)['type'][0] ?? null),
            'URI' => (null !== $v = $xml->URI[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Grant[]
     */
    private function populateResultGrants(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Grant as $item) {
            $items[] = $this->populateResultGrant($item);
        }

        return $items;
    }

    private function populateResultOwner(\SimpleXMLElement $xml): Owner
    {
        return new Owner([
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
        ]);
    }
}
