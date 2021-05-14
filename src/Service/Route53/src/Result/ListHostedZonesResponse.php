<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Route53Client;
use AsyncAws\Route53\ValueObject\HostedZone;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\LinkedService;

/**
 * @implements \IteratorAggregate<HostedZone>
 */
class ListHostedZonesResponse extends Result implements \IteratorAggregate
{
    /**
     * A complex type that contains general information about the hosted zone.
     */
    private $hostedZones = [];

    /**
     * For the second and subsequent calls to `ListHostedZones`, `Marker` is the value that you specified for the `marker`
     * parameter in the request that produced the current response.
     */
    private $marker;

    /**
     * A flag indicating whether there are more hosted zones to be listed. If the response was truncated, you can get more
     * hosted zones by submitting another `ListHostedZones` request and specifying the value of `NextMarker` in the `marker`
     * parameter.
     */
    private $isTruncated;

    /**
     * If `IsTruncated` is `true`, the value of `NextMarker` identifies the first hosted zone in the next group of hosted
     * zones. Submit another `ListHostedZones` request, and specify the value of `NextMarker` from the response in the
     * `marker` parameter.
     */
    private $nextMarker;

    /**
     * The value that you specified for the `maxitems` parameter in the call to `ListHostedZones` that produced the current
     * response.
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
            if ($page->getIsTruncated()) {
                $input->setMarker($page->getNextMarker());

                $this->registerPrefetch($nextPage = $client->ListHostedZones($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getHostedZones(true);

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
            if ($page->getIsTruncated()) {
                $input->setMarker($page->getNextMarker());

                $this->registerPrefetch($nextPage = $client->ListHostedZones($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getHostedZones(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
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
        $this->nextMarker = ($v = $data->NextMarker) ? (string) $v : null;
        $this->maxItems = (string) $data->MaxItems;
    }

    /**
     * @return HostedZone[]
     */
    private function populateResultHostedZones(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->HostedZone as $item) {
            $items[] = new HostedZone([
                'Id' => (string) $item->Id,
                'Name' => (string) $item->Name,
                'CallerReference' => (string) $item->CallerReference,
                'Config' => !$item->Config ? null : new HostedZoneConfig([
                    'Comment' => ($v = $item->Config->Comment) ? (string) $v : null,
                    'PrivateZone' => ($v = $item->Config->PrivateZone) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                ]),
                'ResourceRecordSetCount' => ($v = $item->ResourceRecordSetCount) ? (string) $v : null,
                'LinkedService' => !$item->LinkedService ? null : new LinkedService([
                    'ServicePrincipal' => ($v = $item->LinkedService->ServicePrincipal) ? (string) $v : null,
                    'Description' => ($v = $item->LinkedService->Description) ? (string) $v : null,
                ]),
            ]);
        }

        return $items;
    }
}
