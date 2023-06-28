<?php

namespace AsyncAws\Route53\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Route53\ValueObject\ChangeInfo;
use AsyncAws\Route53\ValueObject\DelegationSet;
use AsyncAws\Route53\ValueObject\HostedZone;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\LinkedService;
use AsyncAws\Route53\ValueObject\VPC;

/**
 * A complex type containing the response information for the hosted zone.
 */
class CreateHostedZoneResponse extends Result
{
    /**
     * A complex type that contains general information about the hosted zone.
     */
    private $hostedZone;

    /**
     * A complex type that contains information about the `CreateHostedZone` request.
     */
    private $changeInfo;

    /**
     * A complex type that describes the name servers for this hosted zone.
     */
    private $delegationSet;

    /**
     * A complex type that contains information about an Amazon VPC that you associated with this hosted zone.
     */
    private $vpc;

    /**
     * The unique URL representing the new hosted zone.
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

        $this->location = $headers['location'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->hostedZone = new HostedZone([
            'Id' => (string) $data->HostedZone->Id,
            'Name' => (string) $data->HostedZone->Name,
            'CallerReference' => (string) $data->HostedZone->CallerReference,
            'Config' => !$data->HostedZone->Config ? null : new HostedZoneConfig([
                'Comment' => ($v = $data->HostedZone->Config->Comment) ? (string) $v : null,
                'PrivateZone' => ($v = $data->HostedZone->Config->PrivateZone) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            ]),
            'ResourceRecordSetCount' => ($v = $data->HostedZone->ResourceRecordSetCount) ? (int) (string) $v : null,
            'LinkedService' => !$data->HostedZone->LinkedService ? null : new LinkedService([
                'ServicePrincipal' => ($v = $data->HostedZone->LinkedService->ServicePrincipal) ? (string) $v : null,
                'Description' => ($v = $data->HostedZone->LinkedService->Description) ? (string) $v : null,
            ]),
        ]);
        $this->changeInfo = new ChangeInfo([
            'Id' => (string) $data->ChangeInfo->Id,
            'Status' => (string) $data->ChangeInfo->Status,
            'SubmittedAt' => new \DateTimeImmutable((string) $data->ChangeInfo->SubmittedAt),
            'Comment' => ($v = $data->ChangeInfo->Comment) ? (string) $v : null,
        ]);
        $this->delegationSet = new DelegationSet([
            'Id' => ($v = $data->DelegationSet->Id) ? (string) $v : null,
            'CallerReference' => ($v = $data->DelegationSet->CallerReference) ? (string) $v : null,
            'NameServers' => $this->populateResultDelegationSetNameServers($data->DelegationSet->NameServers),
        ]);
        $this->vpc = !$data->VPC ? null : new VPC([
            'VPCRegion' => ($v = $data->VPC->VPCRegion) ? (string) $v : null,
            'VPCId' => ($v = $data->VPC->VPCId) ? (string) $v : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultDelegationSetNameServers(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->NameServer as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
