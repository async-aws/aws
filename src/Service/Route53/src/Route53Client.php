<?php

namespace AsyncAws\Route53;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\RequestContext;
use AsyncAws\Route53\Enum\RRType;
use AsyncAws\Route53\Exception\ConflictingDomainExistsException;
use AsyncAws\Route53\Exception\DelegationSetNotAvailableException;
use AsyncAws\Route53\Exception\DelegationSetNotReusableException;
use AsyncAws\Route53\Exception\HostedZoneAlreadyExistsException;
use AsyncAws\Route53\Exception\HostedZoneNotEmptyException;
use AsyncAws\Route53\Exception\InvalidChangeBatchException;
use AsyncAws\Route53\Exception\InvalidDomainNameException;
use AsyncAws\Route53\Exception\InvalidInputException;
use AsyncAws\Route53\Exception\InvalidVPCIdException;
use AsyncAws\Route53\Exception\NoSuchChangeException;
use AsyncAws\Route53\Exception\NoSuchDelegationSetException;
use AsyncAws\Route53\Exception\NoSuchHealthCheckException;
use AsyncAws\Route53\Exception\NoSuchHostedZoneException;
use AsyncAws\Route53\Exception\PriorRequestNotCompleteException;
use AsyncAws\Route53\Exception\TooManyHostedZonesException;
use AsyncAws\Route53\Input\ChangeResourceRecordSetsRequest;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\DeleteHostedZoneRequest;
use AsyncAws\Route53\Input\GetChangeRequest;
use AsyncAws\Route53\Input\ListHostedZonesByNameRequest;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Input\ListResourceRecordSetsRequest;
use AsyncAws\Route53\Result\ChangeResourceRecordSetsResponse;
use AsyncAws\Route53\Result\CreateHostedZoneResponse;
use AsyncAws\Route53\Result\DeleteHostedZoneResponse;
use AsyncAws\Route53\Result\ListHostedZonesByNameResponse;
use AsyncAws\Route53\Result\ListHostedZonesResponse;
use AsyncAws\Route53\Result\ListResourceRecordSetsResponse;
use AsyncAws\Route53\Result\ResourceRecordSetsChangedWaiter;
use AsyncAws\Route53\ValueObject\ChangeBatch;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\VPC;

class Route53Client extends AbstractApi
{
    /**
     * Creates, changes, or deletes a resource record set, which contains authoritative DNS information for a specified
     * domain name or subdomain name. For example, you can use `ChangeResourceRecordSets` to create a resource record set
     * that routes traffic for test.example.com to a web server that has an IP address of 192.0.2.44.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ChangeResourceRecordSets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#changeresourcerecordsets
     *
     * @param array{
     *   HostedZoneId: string,
     *   ChangeBatch: ChangeBatch|array,
     *   @region?: string,
     * }|ChangeResourceRecordSetsRequest $input
     *
     * @throws NoSuchHostedZoneException
     * @throws NoSuchHealthCheckException
     * @throws InvalidChangeBatchException
     * @throws InvalidInputException
     * @throws PriorRequestNotCompleteException
     */
    public function changeResourceRecordSets($input): ChangeResourceRecordSetsResponse
    {
        $input = ChangeResourceRecordSetsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangeResourceRecordSets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchHostedZone' => NoSuchHostedZoneException::class,
            'NoSuchHealthCheck' => NoSuchHealthCheckException::class,
            'InvalidChangeBatch' => InvalidChangeBatchException::class,
            'InvalidInput' => InvalidInputException::class,
            'PriorRequestNotComplete' => PriorRequestNotCompleteException::class,
        ]]));

        return new ChangeResourceRecordSetsResponse($response);
    }

    /**
     * Creates a new public or private hosted zone. You create records in a public hosted zone to define how you want to
     * route traffic on the internet for a domain, such as example.com, and its subdomains (apex.example.com,
     * acme.example.com). You create records in a private hosted zone to define how you want to route traffic for a domain
     * and its subdomains within one or more Amazon Virtual Private Clouds (Amazon VPCs).
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateHostedZone.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#createhostedzone
     *
     * @param array{
     *   Name: string,
     *   VPC?: VPC|array,
     *   CallerReference: string,
     *   HostedZoneConfig?: HostedZoneConfig|array,
     *   DelegationSetId?: string,
     *   @region?: string,
     * }|CreateHostedZoneRequest $input
     *
     * @throws InvalidDomainNameException
     * @throws HostedZoneAlreadyExistsException
     * @throws TooManyHostedZonesException
     * @throws InvalidVPCIdException
     * @throws InvalidInputException
     * @throws DelegationSetNotAvailableException
     * @throws ConflictingDomainExistsException
     * @throws NoSuchDelegationSetException
     * @throws DelegationSetNotReusableException
     */
    public function createHostedZone($input): CreateHostedZoneResponse
    {
        $input = CreateHostedZoneRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateHostedZone', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidDomainName' => InvalidDomainNameException::class,
            'HostedZoneAlreadyExists' => HostedZoneAlreadyExistsException::class,
            'TooManyHostedZones' => TooManyHostedZonesException::class,
            'InvalidVPCId' => InvalidVPCIdException::class,
            'InvalidInput' => InvalidInputException::class,
            'DelegationSetNotAvailable' => DelegationSetNotAvailableException::class,
            'ConflictingDomainExists' => ConflictingDomainExistsException::class,
            'NoSuchDelegationSet' => NoSuchDelegationSetException::class,
            'DelegationSetNotReusable' => DelegationSetNotReusableException::class,
        ]]));

        return new CreateHostedZoneResponse($response);
    }

    /**
     * Deletes a hosted zone.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_DeleteHostedZone.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#deletehostedzone
     *
     * @param array{
     *   Id: string,
     *   @region?: string,
     * }|DeleteHostedZoneRequest $input
     *
     * @throws NoSuchHostedZoneException
     * @throws HostedZoneNotEmptyException
     * @throws PriorRequestNotCompleteException
     * @throws InvalidInputException
     * @throws InvalidDomainNameException
     */
    public function deleteHostedZone($input): DeleteHostedZoneResponse
    {
        $input = DeleteHostedZoneRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteHostedZone', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchHostedZone' => NoSuchHostedZoneException::class,
            'HostedZoneNotEmpty' => HostedZoneNotEmptyException::class,
            'PriorRequestNotComplete' => PriorRequestNotCompleteException::class,
            'InvalidInput' => InvalidInputException::class,
            'InvalidDomainName' => InvalidDomainNameException::class,
        ]]));

        return new DeleteHostedZoneResponse($response);
    }

    /**
     * Retrieves a list of the public and private hosted zones that are associated with the current Amazon Web Services
     * account. The response includes a `HostedZones` child element for each hosted zone.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListHostedZones.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#listhostedzones
     *
     * @param array{
     *   Marker?: string,
     *   MaxItems?: string,
     *   DelegationSetId?: string,
     *   @region?: string,
     * }|ListHostedZonesRequest $input
     *
     * @throws InvalidInputException
     * @throws NoSuchDelegationSetException
     * @throws DelegationSetNotReusableException
     */
    public function listHostedZones($input = []): ListHostedZonesResponse
    {
        $input = ListHostedZonesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListHostedZones', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInput' => InvalidInputException::class,
            'NoSuchDelegationSet' => NoSuchDelegationSetException::class,
            'DelegationSetNotReusable' => DelegationSetNotReusableException::class,
        ]]));

        return new ListHostedZonesResponse($response, $this, $input);
    }

    /**
     * Retrieves a list of your hosted zones in lexicographic order. The response includes a `HostedZones` child element for
     * each hosted zone created by the current Amazon Web Services account.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListHostedZonesByName.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#listhostedzonesbyname
     *
     * @param array{
     *   DNSName?: string,
     *   HostedZoneId?: string,
     *   MaxItems?: string,
     *   @region?: string,
     * }|ListHostedZonesByNameRequest $input
     *
     * @throws InvalidInputException
     * @throws InvalidDomainNameException
     */
    public function listHostedZonesByName($input = []): ListHostedZonesByNameResponse
    {
        $input = ListHostedZonesByNameRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListHostedZonesByName', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInput' => InvalidInputException::class,
            'InvalidDomainName' => InvalidDomainNameException::class,
        ]]));

        return new ListHostedZonesByNameResponse($response);
    }

    /**
     * Lists the resource record sets in a specified hosted zone.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListResourceRecordSets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#listresourcerecordsets
     *
     * @param array{
     *   HostedZoneId: string,
     *   StartRecordName?: string,
     *   StartRecordType?: RRType::*,
     *   StartRecordIdentifier?: string,
     *   MaxItems?: string,
     *   @region?: string,
     * }|ListResourceRecordSetsRequest $input
     *
     * @throws NoSuchHostedZoneException
     * @throws InvalidInputException
     */
    public function listResourceRecordSets($input): ListResourceRecordSetsResponse
    {
        $input = ListResourceRecordSetsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListResourceRecordSets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchHostedZone' => NoSuchHostedZoneException::class,
            'InvalidInput' => InvalidInputException::class,
        ]]));

        return new ListResourceRecordSetsResponse($response, $this, $input);
    }

    /**
     * @see getChange
     *
     * @param array{
     *   Id: string,
     *   @region?: string,
     * }|GetChangeRequest $input
     */
    public function resourceRecordSetsChanged($input): ResourceRecordSetsChangedWaiter
    {
        $input = GetChangeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetChange', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchChange' => NoSuchChangeException::class,
            'InvalidInput' => InvalidInputException::class,
        ]]));

        return new ResourceRecordSetsChangedWaiter($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            return [
                'endpoint' => 'https://route53.amazonaws.com',
                'signRegion' => 'us-east-1',
                'signService' => 'route53',
                'signVersions' => ['v4'],
            ];
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://route53.amazonaws.com.cn',
                    'signRegion' => 'cn-northwest-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://route53.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => 'https://route53.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://route53.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
            case 'fips-aws-global':
                return [
                    'endpoint' => 'https://route53-fips.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
            case 'fips-aws-us-gov-global':
                return [
                    'endpoint' => 'https://route53.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => 'https://route53.amazonaws.com',
            'signRegion' => 'us-east-1',
            'signService' => 'route53',
            'signVersions' => ['v4'],
        ];
    }
}
