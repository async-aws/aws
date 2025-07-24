<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\AliasTarget;
use AsyncAws\Route53\ValueObject\CidrRoutingConfig;
use AsyncAws\Route53\ValueObject\Coordinates;
use AsyncAws\Route53\ValueObject\GeoLocation;
use AsyncAws\Route53\ValueObject\GeoProximityLocation;
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
     *
     * @var ResourceRecordSet[]
     */
    private $resourceRecordSets;

    /**
     * A flag that indicates whether more resource record sets remain to be listed. If your results were truncated, you can
     * make a follow-up pagination request by using the `NextRecordName` element.
     *
     * @var bool
     */
    private $isTruncated;

    /**
     * If the results were truncated, the name of the next record in the list.
     *
     * This element is present only if `IsTruncated` is true.
     *
     * @var string|null
     */
    private $nextRecordName;

    /**
     * If the results were truncated, the type of the next record in the list.
     *
     * This element is present only if `IsTruncated` is true.
     *
     * @var RRType::*|string|null
     */
    private $nextRecordType;

    /**
     * *Resource record sets that have a routing policy other than simple:* If results were truncated for a given DNS name
     * and type, the value of `SetIdentifier` for the next resource record set that has the current DNS name and type.
     *
     * For information about routing policies, see Choosing a Routing Policy [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/routing-policy.html
     *
     * @var string|null
     */
    private $nextRecordIdentifier;

    /**
     * The maximum number of records you requested.
     *
     * @var string
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
     * @return RRType::*|string|null
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
            $page->initialize();
            if ($page->isTruncated) {
                $input->setStartRecordName($page->nextRecordName);

                $input->setStartRecordType($page->nextRecordType);

                $input->setStartRecordIdentifier($page->nextRecordIdentifier);

                $this->registerPrefetch($nextPage = $client->listResourceRecordSets($input));
            } else {
                $nextPage = null;
            }

            yield from $page->resourceRecordSets;

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
        $this->nextRecordName = (null !== $v = $data->NextRecordName[0]) ? (string) $v : null;
        $this->nextRecordType = (null !== $v = $data->NextRecordType[0]) ? (string) $v : null;
        $this->nextRecordIdentifier = (null !== $v = $data->NextRecordIdentifier[0]) ? (string) $v : null;
        $this->maxItems = (string) $data->MaxItems;
    }

    private function populateResultAliasTarget(\SimpleXMLElement $xml): AliasTarget
    {
        return new AliasTarget([
            'HostedZoneId' => (string) $xml->HostedZoneId,
            'DNSName' => (string) $xml->DNSName,
            'EvaluateTargetHealth' => filter_var((string) $xml->EvaluateTargetHealth, \FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    private function populateResultCidrRoutingConfig(\SimpleXMLElement $xml): CidrRoutingConfig
    {
        return new CidrRoutingConfig([
            'CollectionId' => (string) $xml->CollectionId,
            'LocationName' => (string) $xml->LocationName,
        ]);
    }

    private function populateResultCoordinates(\SimpleXMLElement $xml): Coordinates
    {
        return new Coordinates([
            'Latitude' => (string) $xml->Latitude,
            'Longitude' => (string) $xml->Longitude,
        ]);
    }

    private function populateResultGeoLocation(\SimpleXMLElement $xml): GeoLocation
    {
        return new GeoLocation([
            'ContinentCode' => (null !== $v = $xml->ContinentCode[0]) ? (string) $v : null,
            'CountryCode' => (null !== $v = $xml->CountryCode[0]) ? (string) $v : null,
            'SubdivisionCode' => (null !== $v = $xml->SubdivisionCode[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultGeoProximityLocation(\SimpleXMLElement $xml): GeoProximityLocation
    {
        return new GeoProximityLocation([
            'AWSRegion' => (null !== $v = $xml->AWSRegion[0]) ? (string) $v : null,
            'LocalZoneGroup' => (null !== $v = $xml->LocalZoneGroup[0]) ? (string) $v : null,
            'Coordinates' => 0 === $xml->Coordinates->count() ? null : $this->populateResultCoordinates($xml->Coordinates),
            'Bias' => (null !== $v = $xml->Bias[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultResourceRecord(\SimpleXMLElement $xml): ResourceRecord
    {
        return new ResourceRecord([
            'Value' => (string) $xml->Value,
        ]);
    }

    private function populateResultResourceRecordSet(\SimpleXMLElement $xml): ResourceRecordSet
    {
        return new ResourceRecordSet([
            'Name' => (string) $xml->Name,
            'Type' => (string) $xml->Type,
            'SetIdentifier' => (null !== $v = $xml->SetIdentifier[0]) ? (string) $v : null,
            'Weight' => (null !== $v = $xml->Weight[0]) ? (int) (string) $v : null,
            'Region' => (null !== $v = $xml->Region[0]) ? (string) $v : null,
            'GeoLocation' => 0 === $xml->GeoLocation->count() ? null : $this->populateResultGeoLocation($xml->GeoLocation),
            'Failover' => (null !== $v = $xml->Failover[0]) ? (string) $v : null,
            'MultiValueAnswer' => (null !== $v = $xml->MultiValueAnswer[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'TTL' => (null !== $v = $xml->TTL[0]) ? (int) (string) $v : null,
            'ResourceRecords' => (0 === ($v = $xml->ResourceRecords)->count()) ? null : $this->populateResultResourceRecords($v),
            'AliasTarget' => 0 === $xml->AliasTarget->count() ? null : $this->populateResultAliasTarget($xml->AliasTarget),
            'HealthCheckId' => (null !== $v = $xml->HealthCheckId[0]) ? (string) $v : null,
            'TrafficPolicyInstanceId' => (null !== $v = $xml->TrafficPolicyInstanceId[0]) ? (string) $v : null,
            'CidrRoutingConfig' => 0 === $xml->CidrRoutingConfig->count() ? null : $this->populateResultCidrRoutingConfig($xml->CidrRoutingConfig),
            'GeoProximityLocation' => 0 === $xml->GeoProximityLocation->count() ? null : $this->populateResultGeoProximityLocation($xml->GeoProximityLocation),
        ]);
    }

    /**
     * @return ResourceRecordSet[]
     */
    private function populateResultResourceRecordSets(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->ResourceRecordSet as $item) {
            $items[] = $this->populateResultResourceRecordSet($item);
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
            $items[] = $this->populateResultResourceRecord($item);
        }

        return $items;
    }
}
