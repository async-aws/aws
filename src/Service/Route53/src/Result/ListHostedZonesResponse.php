<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Enum\AcceleratedRecoveryStatus;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\HostedZone;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\HostedZoneFailureReasons;
use AsyncAws\Route53\ValueObject\HostedZoneFeatures;
use AsyncAws\Route53\ValueObject\LinkedService;

/**
 * @implements \IteratorAggregate<HostedZone>
 */
class ListHostedZonesResponse extends Result implements \IteratorAggregate
{
    /**
     * A complex type that contains general information about the hosted zone.
     *
     * @var HostedZone[]
     */
    private $hostedZones;

    /**
     * For the second and subsequent calls to `ListHostedZones`, `Marker` is the value that you specified for the `marker`
     * parameter in the request that produced the current response.
     *
     * @var string
     */
    private $marker;

    /**
     * A flag indicating whether there are more hosted zones to be listed. If the response was truncated, you can get more
     * hosted zones by submitting another `ListHostedZones` request and specifying the value of `NextMarker` in the `marker`
     * parameter.
     *
     * @var bool
     */
    private $isTruncated;

    /**
     * If `IsTruncated` is `true`, the value of `NextMarker` identifies the first hosted zone in the next group of hosted
     * zones. Submit another `ListHostedZones` request, and specify the value of `NextMarker` from the response in the
     * `marker` parameter.
     *
     * This element is present only if `IsTruncated` is `true`.
     *
     * @var string|null
     */
    private $nextMarker;

    /**
     * The value that you specified for the `maxitems` parameter in the call to `ListHostedZones` that produced the current
     * response.
     *
     * @var string
     */
    private $maxItems;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<HostedZone>
     */
    public function getHostedZones(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->hostedZones;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof Route53Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListHostedZonesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setMarker($page->nextMarker);

                $this->registerPrefetch($nextPage = $client->listHostedZones($input));
            } else {
                $nextPage = null;
            }

            yield from $page->hostedZones;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getIsTruncated(): bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    /**
     * Iterates over HostedZones.
     *
     * @return \Traversable<HostedZone>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getHostedZones();
    }

    public function getMarker(): string
    {
        $this->initialize();

        return $this->marker;
    }

    public function getMaxItems(): string
    {
        $this->initialize();

        return $this->maxItems;
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->nextMarker;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->hostedZones = $this->populateResultHostedZones($data->HostedZones);
        $this->marker = (string) $data->Marker;
        $this->isTruncated = filter_var((string) $data->IsTruncated, \FILTER_VALIDATE_BOOLEAN);
        $this->nextMarker = (null !== $v = $data->NextMarker[0]) ? (string) $v : null;
        $this->maxItems = (string) $data->MaxItems;
    }

    private function populateResultHostedZone(\SimpleXMLElement $xml): HostedZone
    {
        return new HostedZone([
            'Id' => (string) $xml->Id,
            'Name' => (string) $xml->Name,
            'CallerReference' => (string) $xml->CallerReference,
            'Config' => 0 === $xml->Config->count() ? null : $this->populateResultHostedZoneConfig($xml->Config),
            'ResourceRecordSetCount' => (null !== $v = $xml->ResourceRecordSetCount[0]) ? (int) (string) $v : null,
            'LinkedService' => 0 === $xml->LinkedService->count() ? null : $this->populateResultLinkedService($xml->LinkedService),
            'Features' => 0 === $xml->Features->count() ? null : $this->populateResultHostedZoneFeatures($xml->Features),
        ]);
    }

    private function populateResultHostedZoneConfig(\SimpleXMLElement $xml): HostedZoneConfig
    {
        return new HostedZoneConfig([
            'Comment' => (null !== $v = $xml->Comment[0]) ? (string) $v : null,
            'PrivateZone' => (null !== $v = $xml->PrivateZone[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    private function populateResultHostedZoneFailureReasons(\SimpleXMLElement $xml): HostedZoneFailureReasons
    {
        return new HostedZoneFailureReasons([
            'AcceleratedRecovery' => (null !== $v = $xml->AcceleratedRecovery[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultHostedZoneFeatures(\SimpleXMLElement $xml): HostedZoneFeatures
    {
        return new HostedZoneFeatures([
            'AcceleratedRecoveryStatus' => (null !== $v = $xml->AcceleratedRecoveryStatus[0]) ? (!AcceleratedRecoveryStatus::exists((string) $xml->AcceleratedRecoveryStatus) ? AcceleratedRecoveryStatus::UNKNOWN_TO_SDK : (string) $xml->AcceleratedRecoveryStatus) : null,
            'FailureReasons' => 0 === $xml->FailureReasons->count() ? null : $this->populateResultHostedZoneFailureReasons($xml->FailureReasons),
        ]);
    }

    /**
     * @return HostedZone[]
     */
    private function populateResultHostedZones(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->HostedZone as $item) {
            $items[] = $this->populateResultHostedZone($item);
        }

        return $items;
    }

    private function populateResultLinkedService(\SimpleXMLElement $xml): LinkedService
    {
        return new LinkedService([
            'ServicePrincipal' => (null !== $v = $xml->ServicePrincipal[0]) ? (string) $v : null,
            'Description' => (null !== $v = $xml->Description[0]) ? (string) $v : null,
        ]);
    }
}
