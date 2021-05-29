<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\AliasTarget;
use AsyncAws\Route53\ValueObject\GeoLocation;
use AsyncAws\Route53\ValueObject\ResourceRecord;
use AsyncAws\Route53\ValueObject\ResourceRecordSet;

/**
 * A complex type that contains list information for the resource record set.
 *
 * @implements \IteratorAggregate<ResourceRecordSet>
 */
class ListResourceRecordSetsResponse extends Result implements \IteratorAggregate
{
    /**
     * Information about multiple resource record sets.
     */
    private $resourceRecordSets = [];

    /**
     * A flag that indicates whether more resource record sets remain to be listed. If your results were truncated, you can
     * make a follow-up pagination request by using the `NextRecordName` element.
     */
    private $isTruncated;

    /**
     * If the results were truncated, the name of the next record in the list.
     */
    private $nextRecordName;

    /**
     * If the results were truncated, the type of the next record in the list.
     */
    private $nextRecordType;

    /**
     * *Resource record sets that have a routing policy other than simple:* If results were truncated for a given DNS name
     * and type, the value of `SetIdentifier` for the next resource record set that has the current DNS name and type.
     */
    private $nextRecordIdentifier;

    /**
     * The maximum number of records you requested.
     */
    private $maxItems;

    public function getIsTruncated(): bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    /**
     * Iterates over ResourceRecordSets.
     *
     * @return \Traversable<ResourceRecordSet>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getResourceRecordSets();
    }

    public function getMaxItems(): string
    {
        $this->initialize();

        return $this->maxItems;
    }

    public function getNextRecordIdentifier(): ?string
    {
        $this->initialize();

        return $this->nextRecordIdentifier;
    }

    public function getNextRecordName(): ?string
    {
        $this->initialize();

        return $this->nextRecordName;
    }

    /**
     * @return RRType::*|null
     */
    public function getNextRecordType(): ?string
    {
        $this->initialize();

        return $this->nextRecordType;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ResourceRecordSet>
     */
    public function getResourceRecordSets(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->resourceRecordSets;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof Route53Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListResourceRecordSetsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getIsTruncated()) {
                $input->setStartRecordName($page->getNextRecordName());

                $input->setStartRecordType($page->getNextRecordType());

                $input->setStartRecordIdentifier($page->getNextRecordIdentifier());

                $this->registerPrefetch($nextPage = $client->listResourceRecordSets($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getResourceRecordSets(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->resourceRecordSets = $this->populateResultResourceRecordSets($data->ResourceRecordSets);
        $this->isTruncated = filter_var((string) $data->IsTruncated, \FILTER_VALIDATE_BOOLEAN);
        $this->nextRecordName = ($v = $data->NextRecordName) ? (string) $v : null;
        $this->nextRecordType = ($v = $data->NextRecordType) ? (string) $v : null;
        $this->nextRecordIdentifier = ($v = $data->NextRecordIdentifier) ? (string) $v : null;
        $this->maxItems = (string) $data->MaxItems;
    }

    /**
     * @return ResourceRecordSet[]
     */
    private function populateResultResourceRecordSets(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->ResourceRecordSet as $item) {
            $items[] = new ResourceRecordSet([
                'Name' => (string) $item->Name,
                'Type' => (string) $item->Type,
                'SetIdentifier' => ($v = $item->SetIdentifier) ? (string) $v : null,
                'Weight' => ($v = $item->Weight) ? (string) $v : null,
                'Region' => ($v = $item->Region) ? (string) $v : null,
                'GeoLocation' => !$item->GeoLocation ? null : new GeoLocation([
                    'ContinentCode' => ($v = $item->GeoLocation->ContinentCode) ? (string) $v : null,
                    'CountryCode' => ($v = $item->GeoLocation->CountryCode) ? (string) $v : null,
                    'SubdivisionCode' => ($v = $item->GeoLocation->SubdivisionCode) ? (string) $v : null,
                ]),
                'Failover' => ($v = $item->Failover) ? (string) $v : null,
                'MultiValueAnswer' => ($v = $item->MultiValueAnswer) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'TTL' => ($v = $item->TTL) ? (string) $v : null,
                'ResourceRecords' => !$item->ResourceRecords ? [] : $this->populateResultResourceRecords($item->ResourceRecords),
                'AliasTarget' => !$item->AliasTarget ? null : new AliasTarget([
                    'HostedZoneId' => (string) $item->AliasTarget->HostedZoneId,
                    'DNSName' => (string) $item->AliasTarget->DNSName,
                    'EvaluateTargetHealth' => filter_var((string) $item->AliasTarget->EvaluateTargetHealth, \FILTER_VALIDATE_BOOLEAN),
                ]),
                'HealthCheckId' => ($v = $item->HealthCheckId) ? (string) $v : null,
                'TrafficPolicyInstanceId' => ($v = $item->TrafficPolicyInstanceId) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return ResourceRecord[]
     */
    private function populateResultResourceRecords(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->ResourceRecord as $item) {
            $items[] = new ResourceRecord([
                'Value' => (string) $item->Value,
            ]);
        }

        return $items;
    }
}
