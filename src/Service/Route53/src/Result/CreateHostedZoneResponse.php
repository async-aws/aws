<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\Enum\AcceleratedRecoveryStatus;
use AsyncAws\Route53\Enum\ChangeStatus;
use AsyncAws\Route53\Enum\VPCRegion;
use AsyncAws\Route53\ValueObject\ChangeInfo;
use AsyncAws\Route53\ValueObject\DelegationSet;
use AsyncAws\Route53\ValueObject\HostedZone;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\HostedZoneFailureReasons;
use AsyncAws\Route53\ValueObject\HostedZoneFeatures;
use AsyncAws\Route53\ValueObject\LinkedService;
use AsyncAws\Route53\ValueObject\VPC;

/**
 * A complex type containing the response information for the hosted zone.
 */
class CreateHostedZoneResponse extends Result
{
    /**
     * A complex type that contains general information about the hosted zone.
     *
     * @var HostedZone
     */
    private $hostedZone;

    /**
     * A complex type that contains information about the `CreateHostedZone` request.
     *
     * @var ChangeInfo
     */
    private $changeInfo;

    /**
     * A complex type that describes the name servers for this hosted zone.
     *
     * @var DelegationSet
     */
    private $delegationSet;

    /**
     * A complex type that contains information about an Amazon VPC that you associated with this hosted zone.
     *
     * @var VPC|null
     */
    private $vpc;

    /**
     * The unique URL representing the new hosted zone.
     *
     * @var string
     */
    private $location;

    public function getChangeInfo(): ChangeInfo
    {
        $this->initialize();

        return $this->changeInfo;
    }

    public function getDelegationSet(): DelegationSet
    {
        $this->initialize();

        return $this->delegationSet;
    }

    public function getHostedZone(): HostedZone
    {
        $this->initialize();

        return $this->hostedZone;
    }

    public function getLocation(): string
    {
        $this->initialize();

        return $this->location;
    }

    public function getVpc(): ?VPC
    {
        $this->initialize();

        return $this->vpc;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->location = $headers['location'][0];

        $data = new \SimpleXMLElement($response->getContent());
        $this->hostedZone = $this->populateResultHostedZone($data->HostedZone);
        $this->changeInfo = $this->populateResultChangeInfo($data->ChangeInfo);
        $this->delegationSet = $this->populateResultDelegationSet($data->DelegationSet);
        $this->vpc = 0 === $data->VPC->count() ? null : $this->populateResultVPC($data->VPC);
    }

    private function populateResultChangeInfo(\SimpleXMLElement $xml): ChangeInfo
    {
        return new ChangeInfo([
            'Id' => (string) $xml->Id,
            'Status' => !ChangeStatus::exists((string) $xml->Status) ? ChangeStatus::UNKNOWN_TO_SDK : (string) $xml->Status,
            'SubmittedAt' => new \DateTimeImmutable((string) $xml->SubmittedAt),
            'Comment' => (null !== $v = $xml->Comment[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultDelegationSet(\SimpleXMLElement $xml): DelegationSet
    {
        return new DelegationSet([
            'Id' => (null !== $v = $xml->Id[0]) ? (string) $v : null,
            'CallerReference' => (null !== $v = $xml->CallerReference[0]) ? (string) $v : null,
            'NameServers' => $this->populateResultDelegationSetNameServers($xml->NameServers),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultDelegationSetNameServers(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->NameServer as $item) {
            $items[] = (string) $item;
        }

        return $items;
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

    private function populateResultLinkedService(\SimpleXMLElement $xml): LinkedService
    {
        return new LinkedService([
            'ServicePrincipal' => (null !== $v = $xml->ServicePrincipal[0]) ? (string) $v : null,
            'Description' => (null !== $v = $xml->Description[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultVPC(\SimpleXMLElement $xml): VPC
    {
        return new VPC([
            'VPCRegion' => (null !== $v = $xml->VPCRegion[0]) ? (!VPCRegion::exists((string) $xml->VPCRegion) ? VPCRegion::UNKNOWN_TO_SDK : (string) $xml->VPCRegion) : null,
            'VPCId' => (null !== $v = $xml->VPCId[0]) ? (string) $v : null,
        ]);
    }
}
