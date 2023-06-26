<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\ValueObject\HostedZone;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\LinkedService;

/**
 * A complex type that contains the response information for the request.
 */
class ListHostedZonesByNameResponse extends Result
{
    /**
     * A complex type that contains general information about the hosted zone.
     */
    private $hostedZones;

    /**
     * For the second and subsequent calls to `ListHostedZonesByName`, `DNSName` is the value that you specified for the
     * `dnsname` parameter in the request that produced the current response.
     */
    private $dnsName;

    /**
     * The ID that Amazon Route 53 assigned to the hosted zone when you created it.
     */
    private $hostedZoneId;

    /**
     * A flag that indicates whether there are more hosted zones to be listed. If the response was truncated, you can get
     * the next group of `maxitems` hosted zones by calling `ListHostedZonesByName` again and specifying the values of
     * `NextDNSName` and `NextHostedZoneId` elements in the `dnsname` and `hostedzoneid` parameters.
     */
    private $isTruncated;

    /**
     * If `IsTruncated` is true, the value of `NextDNSName` is the name of the first hosted zone in the next group of
     * `maxitems` hosted zones. Call `ListHostedZonesByName` again and specify the value of `NextDNSName` and
     * `NextHostedZoneId` in the `dnsname` and `hostedzoneid` parameters, respectively.
     *
     * This element is present only if `IsTruncated` is `true`.
     */
    private $nextDnsName;

    /**
     * If `IsTruncated` is `true`, the value of `NextHostedZoneId` identifies the first hosted zone in the next group of
     * `maxitems` hosted zones. Call `ListHostedZonesByName` again and specify the value of `NextDNSName` and
     * `NextHostedZoneId` in the `dnsname` and `hostedzoneid` parameters, respectively.
     *
     * This element is present only if `IsTruncated` is `true`.
     */
    private $nextHostedZoneId;

    /**
     * The value that you specified for the `maxitems` parameter in the call to `ListHostedZonesByName` that produced the
     * current response.
     */
    private $maxItems;

    public function getDnsName(): ?string
    {
        $this->initialize();

        return $this->dnsName;
    }

    public function getHostedZoneId(): ?string
    {
        $this->initialize();

        return $this->hostedZoneId;
    }

    /**
     * @return HostedZone[]
     */
    public function getHostedZones(): array
    {
        $this->initialize();

        return $this->hostedZones;
    }

    public function getIsTruncated(): bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    public function getMaxItems(): string
    {
        $this->initialize();

        return $this->maxItems;
    }

    public function getNextDnsName(): ?string
    {
        $this->initialize();

        return $this->nextDnsName;
    }

    public function getNextHostedZoneId(): ?string
    {
        $this->initialize();

        return $this->nextHostedZoneId;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->hostedZones = $this->populateResultHostedZones($data->HostedZones);
        $this->dnsName = ($v = $data->DNSName) ? (string) $v : null;
        $this->hostedZoneId = ($v = $data->HostedZoneId) ? (string) $v : null;
        $this->isTruncated = filter_var((string) $data->IsTruncated, \FILTER_VALIDATE_BOOLEAN);
        $this->nextDnsName = ($v = $data->NextDNSName) ? (string) $v : null;
        $this->nextHostedZoneId = ($v = $data->NextHostedZoneId) ? (string) $v : null;
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
                'ResourceRecordSetCount' => ($v = $item->ResourceRecordSetCount) ? (int) (string) $v : null,
                'LinkedService' => !$item->LinkedService ? null : new LinkedService([
                    'ServicePrincipal' => ($v = $item->LinkedService->ServicePrincipal) ? (string) $v : null,
                    'Description' => ($v = $item->LinkedService->Description) ? (string) $v : null,
                ]),
            ]);
        }

        return $items;
    }
}
