<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Enum\AcceleratedRecoveryStatus;
use AsyncAws\Route53\ValueObject\HostedZone;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\HostedZoneFailureReasons;
use AsyncAws\Route53\ValueObject\HostedZoneFeatures;
use AsyncAws\Route53\ValueObject\LinkedService;

/**
 * A complex type that contains the response information for the request.
 */
class ListHostedZonesByNameResponse extends Result
{
    /**
     * A complex type that contains general information about the hosted zone.
     *
     * @var HostedZone[]
     */
    private $hostedZones;

    /**
     * For the second and subsequent calls to `ListHostedZonesByName`, `DNSName` is the value that you specified for the
     * `dnsname` parameter in the request that produced the current response.
     *
     * @var string|null
     */
    private $dnsName;

    /**
     * The ID that Amazon Route 53 assigned to the hosted zone when you created it.
     *
     * @var string|null
     */
    private $hostedZoneId;

    /**
     * A flag that indicates whether there are more hosted zones to be listed. If the response was truncated, you can get
     * the next group of `maxitems` hosted zones by calling `ListHostedZonesByName` again and specifying the values of
     * `NextDNSName` and `NextHostedZoneId` elements in the `dnsname` and `hostedzoneid` parameters.
     *
     * @var bool
     */
    private $isTruncated;

    /**
     * If `IsTruncated` is true, the value of `NextDNSName` is the name of the first hosted zone in the next group of
     * `maxitems` hosted zones. Call `ListHostedZonesByName` again and specify the value of `NextDNSName` and
     * `NextHostedZoneId` in the `dnsname` and `hostedzoneid` parameters, respectively.
     *
     * This element is present only if `IsTruncated` is `true`.
     *
     * @var string|null
     */
    private $nextDnsName;

    /**
     * If `IsTruncated` is `true`, the value of `NextHostedZoneId` identifies the first hosted zone in the next group of
     * `maxitems` hosted zones. Call `ListHostedZonesByName` again and specify the value of `NextDNSName` and
     * `NextHostedZoneId` in the `dnsname` and `hostedzoneid` parameters, respectively.
     *
     * This element is present only if `IsTruncated` is `true`.
     *
     * @var string|null
     */
    private $nextHostedZoneId;

    /**
     * The value that you specified for the `maxitems` parameter in the call to `ListHostedZonesByName` that produced the
     * current response.
     *
     * @var string
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
        $this->dnsName = (null !== $v = $data->DNSName[0]) ? (string) $v : null;
        $this->hostedZoneId = (null !== $v = $data->HostedZoneId[0]) ? (string) $v : null;
        $this->isTruncated = filter_var((string) $data->IsTruncated, \FILTER_VALIDATE_BOOLEAN);
        $this->nextDnsName = (null !== $v = $data->NextDNSName[0]) ? (string) $v : null;
        $this->nextHostedZoneId = (null !== $v = $data->NextHostedZoneId[0]) ? (string) $v : null;
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
