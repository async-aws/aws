<?php

namespace AsyncAws\Route53;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\RequestContext;
use AsyncAws\Route53\Exception\ConflictingDomainExistsException;
use AsyncAws\Route53\Exception\DelegationSetNotAvailableException;
use AsyncAws\Route53\Exception\DelegationSetNotReusableException;
use AsyncAws\Route53\Exception\HostedZoneAlreadyExistsException;
use AsyncAws\Route53\Exception\InvalidDomainNameException;
use AsyncAws\Route53\Exception\InvalidInputException;
use AsyncAws\Route53\Exception\InvalidVPCIdException;
use AsyncAws\Route53\Exception\NoSuchDelegationSetException;
use AsyncAws\Route53\Exception\TooManyHostedZonesException;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\Input\ListHostedZonesRequest;
use AsyncAws\Route53\Result\CreateHostedZoneResponse;
use AsyncAws\Route53\Result\ListHostedZonesResponse;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\VPC;

class Route53Client extends AbstractApi
{
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
     * Retrieves a list of the public and private hosted zones that are associated with the current AWS account. The
     * response includes a `HostedZones` child element for each hosted zone.
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
