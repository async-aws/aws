<?php

namespace AsyncAws\Route53;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\RequestContext;
use AsyncAws\Route53\Enum\HostedZoneType;
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
     * **Deleting Resource Record Sets**
     *
     * To delete a resource record set, you must specify all the same values that you specified when you created it.
     *
     * **Change Batches and Transactional Changes**
     *
     * The request body must include a document with a `ChangeResourceRecordSetsRequest` element. The request body contains
     * a list of change items, known as a change batch. Change batches are considered transactional changes. Route 53
     * validates the changes in the request and then either makes all or none of the changes in the change batch request.
     * This ensures that DNS routing isn't adversely affected by partial changes to the resource record sets in a hosted
     * zone.
     *
     * For example, suppose a change batch request contains two changes: it deletes the `CNAME` resource record set for
     * www.example.com and creates an alias resource record set for www.example.com. If validation for both records
     * succeeds, Route 53 deletes the first resource record set and creates the second resource record set in a single
     * operation. If validation for either the `DELETE` or the `CREATE` action fails, then the request is canceled, and the
     * original `CNAME` record continues to exist.
     *
     * > If you try to delete the same resource record set more than once in a single change batch, Route 53 returns an
     * > `InvalidChangeBatch` error.
     *
     * **Traffic Flow**
     *
     * To create resource record sets for complex routing configurations, use either the traffic flow visual editor in the
     * Route 53 console or the API actions for traffic policies and traffic policy instances. Save the configuration as a
     * traffic policy, then associate the traffic policy with one or more domain names (such as example.com) or subdomain
     * names (such as www.example.com), in the same hosted zone or in multiple hosted zones. You can roll back the updates
     * if the new configuration isn't performing as expected. For more information, see Using Traffic Flow to Route DNS
     * Traffic [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * **Create, Delete, and Upsert**
     *
     * Use `ChangeResourceRecordsSetsRequest` to perform the following actions:
     *
     * - `CREATE`: Creates a resource record set that has the specified values.
     * - `DELETE`: Deletes an existing resource record set that has the specified values.
     * - `UPSERT`: If a resource set doesn't exist, Route 53 creates it. If a resource set exists Route 53 updates it with
     *   the values in the request.
     *
     * **Syntaxes for Creating, Updating, and Deleting Resource Record Sets**
     *
     * The syntax for a request depends on the type of resource record set that you want to create, delete, or update, such
     * as weighted, alias, or failover. The XML elements in your request must appear in the order listed in the syntax.
     *
     * For an example for each type of resource record set, see "Examples."
     *
     * Don't refer to the syntax in the "Parameter Syntax" section, which includes all of the elements for every kind of
     * resource record set that you can create, delete, or update by using `ChangeResourceRecordSets`.
     *
     * **Change Propagation to Route 53 DNS Servers**
     *
     * When you submit a `ChangeResourceRecordSets` request, Route 53 propagates your changes to all of the Route 53
     * authoritative DNS servers managing the hosted zone. While your changes are propagating, `GetChange` returns a status
     * of `PENDING`. When propagation is complete, `GetChange` returns a status of `INSYNC`. Changes generally propagate to
     * all Route 53 name servers managing the hosted zone within 60 seconds. For more information, see GetChange [^2].
     *
     * **Limits on ChangeResourceRecordSets Requests**
     *
     * For information about the limits on a `ChangeResourceRecordSets` request, see Limits [^3] in the *Amazon Route 53
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/traffic-flow.html
     * [^2]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetChange.html
     * [^3]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/DNSLimitations.html
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ChangeResourceRecordSets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#changeresourcerecordsets
     *
     * @param array{
     *   HostedZoneId: string,
     *   ChangeBatch: ChangeBatch|array,
     *   '@region'?: string|null,
     * }|ChangeResourceRecordSetsRequest $input
     *
     * @throws InvalidChangeBatchException
     * @throws InvalidInputException
     * @throws NoSuchHealthCheckException
     * @throws NoSuchHostedZoneException
     * @throws PriorRequestNotCompleteException
     */
    public function changeResourceRecordSets($input): ChangeResourceRecordSetsResponse
    {
        $input = ChangeResourceRecordSetsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangeResourceRecordSets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidChangeBatch' => InvalidChangeBatchException::class,
            'InvalidInput' => InvalidInputException::class,
            'NoSuchHealthCheck' => NoSuchHealthCheckException::class,
            'NoSuchHostedZone' => NoSuchHostedZoneException::class,
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
     * ! You can't convert a public hosted zone to a private hosted zone or vice versa. Instead, you must create a new
     * ! hosted zone with the same name and create new resource record sets.
     *
     * For more information about charges for hosted zones, see Amazon Route 53 Pricing [^1].
     *
     * Note the following:
     *
     * - You can't create a hosted zone for a top-level domain (TLD) such as .com.
     * - For public hosted zones, Route 53 automatically creates a default SOA record and four NS records for the zone. For
     *   more information about SOA and NS records, see NS and SOA Records that Route 53 Creates for a Hosted Zone [^2] in
     *   the *Amazon Route 53 Developer Guide*.
     *
     *   If you want to use the same name servers for multiple public hosted zones, you can optionally associate a reusable
     *   delegation set with the hosted zone. See the `DelegationSetId` element.
     * - If your domain is registered with a registrar other than Route 53, you must update the name servers with your
     *   registrar to make Route 53 the DNS service for the domain. For more information, see Migrating DNS Service for an
     *   Existing Domain to Amazon Route 53 [^3] in the *Amazon Route 53 Developer Guide*.
     *
     * When you submit a `CreateHostedZone` request, the initial status of the hosted zone is `PENDING`. For public hosted
     * zones, this means that the NS and SOA records are not yet available on all Route 53 DNS servers. When the NS and SOA
     * records are available, the status of the zone changes to `INSYNC`.
     *
     * The `CreateHostedZone` request requires the caller to have an `ec2:DescribeVpcs` permission.
     *
     * > When creating private hosted zones, the Amazon VPC must belong to the same partition where the hosted zone is
     * > created. A partition is a group of Amazon Web Services Regions. Each Amazon Web Services account is scoped to one
     * > partition.
     * >
     * > The following are the supported partitions:
     * >
     * > - `aws` - Amazon Web Services Regions
     * > - `aws-cn` - China Regions
     * > - `aws-us-gov` - Amazon Web Services GovCloud (US) Region
     * >
     * > For more information, see Access Management [^4] in the *Amazon Web Services General Reference*.
     *
     * [^1]: http://aws.amazon.com/route53/pricing/
     * [^2]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/SOA-NSrecords.html
     * [^3]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/MigratingDNS.html
     * [^4]: https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateHostedZone.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#createhostedzone
     *
     * @param array{
     *   Name: string,
     *   VPC?: VPC|array|null,
     *   CallerReference: string,
     *   HostedZoneConfig?: HostedZoneConfig|array|null,
     *   DelegationSetId?: string|null,
     *   '@region'?: string|null,
     * }|CreateHostedZoneRequest $input
     *
     * @throws ConflictingDomainExistsException
     * @throws DelegationSetNotAvailableException
     * @throws DelegationSetNotReusableException
     * @throws HostedZoneAlreadyExistsException
     * @throws InvalidDomainNameException
     * @throws InvalidInputException
     * @throws InvalidVPCIdException
     * @throws NoSuchDelegationSetException
     * @throws TooManyHostedZonesException
     */
    public function createHostedZone($input): CreateHostedZoneResponse
    {
        $input = CreateHostedZoneRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateHostedZone', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConflictingDomainExists' => ConflictingDomainExistsException::class,
            'DelegationSetNotAvailable' => DelegationSetNotAvailableException::class,
            'DelegationSetNotReusable' => DelegationSetNotReusableException::class,
            'HostedZoneAlreadyExists' => HostedZoneAlreadyExistsException::class,
            'InvalidDomainName' => InvalidDomainNameException::class,
            'InvalidInput' => InvalidInputException::class,
            'InvalidVPCId' => InvalidVPCIdException::class,
            'NoSuchDelegationSet' => NoSuchDelegationSetException::class,
            'TooManyHostedZones' => TooManyHostedZonesException::class,
        ]]));

        return new CreateHostedZoneResponse($response);
    }

    /**
     * Deletes a hosted zone.
     *
     * If the hosted zone was created by another service, such as Cloud Map, see Deleting Public Hosted Zones That Were
     * Created by Another Service [^1] in the *Amazon Route 53 Developer Guide* for information about how to delete it.
     * (The process is the same for public and private hosted zones that were created by another service.)
     *
     * If you want to keep your domain registration but you want to stop routing internet traffic to your website or web
     * application, we recommend that you delete resource record sets in the hosted zone instead of deleting the hosted
     * zone.
     *
     * ! If you delete a hosted zone, you can't undelete it. You must create a new hosted zone and update the name servers
     * ! for your domain registration, which can require up to 48 hours to take effect. (If you delegated responsibility for
     * ! a subdomain to a hosted zone and you delete the child hosted zone, you must update the name servers in the parent
     * ! hosted zone.) In addition, if you delete a hosted zone, someone could hijack the domain and route traffic to their
     * ! own resources using your domain name.
     *
     * If you want to avoid the monthly charge for the hosted zone, you can transfer DNS service for the domain to a free
     * DNS service. When you transfer DNS service, you have to update the name servers for the domain registration. If the
     * domain is registered with Route 53, see UpdateDomainNameservers [^2] for information about how to replace Route 53
     * name servers with name servers for the new DNS service. If the domain is registered with another registrar, use the
     * method provided by the registrar to update name servers for the domain registration. For more information, perform an
     * internet search on "free DNS service."
     *
     * You can delete a hosted zone only if it contains only the default SOA and NS records and has DNSSEC signing disabled.
     * If the hosted zone contains other records or has DNSSEC enabled, you must delete the records and disable DNSSEC
     * before deletion. Attempting to delete a hosted zone with additional records or DNSSEC enabled returns a
     * `HostedZoneNotEmpty` error. For information about deleting records, see ChangeResourceRecordSets [^3].
     *
     * To verify that the hosted zone has been deleted, do one of the following:
     *
     * - Use the `GetHostedZone` action to request information about the hosted zone.
     * - Use the `ListHostedZones` action to get a list of the hosted zones associated with the current Amazon Web Services
     *   account.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/DeleteHostedZone.html#delete-public-hosted-zone-created-by-another-service
     * [^2]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_domains_UpdateDomainNameservers.html
     * [^3]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_ChangeResourceRecordSets.html
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_DeleteHostedZone.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#deletehostedzone
     *
     * @param array{
     *   Id: string,
     *   '@region'?: string|null,
     * }|DeleteHostedZoneRequest $input
     *
     * @throws HostedZoneNotEmptyException
     * @throws InvalidDomainNameException
     * @throws InvalidInputException
     * @throws NoSuchHostedZoneException
     * @throws PriorRequestNotCompleteException
     */
    public function deleteHostedZone($input): DeleteHostedZoneResponse
    {
        $input = DeleteHostedZoneRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteHostedZone', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'HostedZoneNotEmpty' => HostedZoneNotEmptyException::class,
            'InvalidDomainName' => InvalidDomainNameException::class,
            'InvalidInput' => InvalidInputException::class,
            'NoSuchHostedZone' => NoSuchHostedZoneException::class,
            'PriorRequestNotComplete' => PriorRequestNotCompleteException::class,
        ]]));

        return new DeleteHostedZoneResponse($response);
    }

    /**
     * Retrieves a list of the public and private hosted zones that are associated with the current Amazon Web Services
     * account. The response includes a `HostedZones` child element for each hosted zone.
     *
     * Amazon Route 53 returns a maximum of 100 items in each response. If you have a lot of hosted zones, you can use the
     * `maxitems` parameter to list them in groups of up to 100.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListHostedZones.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#listhostedzones
     *
     * @param array{
     *   Marker?: string|null,
     *   MaxItems?: string|null,
     *   DelegationSetId?: string|null,
     *   HostedZoneType?: HostedZoneType::*|null,
     *   '@region'?: string|null,
     * }|ListHostedZonesRequest $input
     *
     * @throws DelegationSetNotReusableException
     * @throws InvalidInputException
     * @throws NoSuchDelegationSetException
     */
    public function listHostedZones($input = []): ListHostedZonesResponse
    {
        $input = ListHostedZonesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListHostedZones', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DelegationSetNotReusable' => DelegationSetNotReusableException::class,
            'InvalidInput' => InvalidInputException::class,
            'NoSuchDelegationSet' => NoSuchDelegationSetException::class,
        ]]));

        return new ListHostedZonesResponse($response, $this, $input);
    }

    /**
     * Retrieves a list of your hosted zones in lexicographic order. The response includes a `HostedZones` child element for
     * each hosted zone created by the current Amazon Web Services account.
     *
     * `ListHostedZonesByName` sorts hosted zones by name with the labels reversed. For example:
     *
     * `com.example.www.`
     *
     * Note the trailing dot, which can change the sort order in some circumstances.
     *
     * If the domain name includes escape characters or Punycode, `ListHostedZonesByName` alphabetizes the domain name using
     * the escaped or Punycoded value, which is the format that Amazon Route 53 saves in its database. For example, to
     * create a hosted zone for exämple.com, you specify ex\344mple.com for the domain name. `ListHostedZonesByName`
     * alphabetizes it as:
     *
     * `com.ex\344mple.`
     *
     * The labels are reversed and alphabetized using the escaped value. For more information about valid domain name
     * formats, including internationalized domain names, see DNS Domain Name Format [^1] in the *Amazon Route 53 Developer
     * Guide*.
     *
     * Route 53 returns up to 100 items in each response. If you have a lot of hosted zones, use the `MaxItems` parameter to
     * list them in groups of up to 100. The response includes values that help navigate from one group of `MaxItems` hosted
     * zones to the next:
     *
     * - The `DNSName` and `HostedZoneId` elements in the response contain the values, if any, specified for the `dnsname`
     *   and `hostedzoneid` parameters in the request that produced the current response.
     * - The `MaxItems` element in the response contains the value, if any, that you specified for the `maxitems` parameter
     *   in the request that produced the current response.
     * - If the value of `IsTruncated` in the response is true, there are more hosted zones associated with the current
     *   Amazon Web Services account.
     *
     *   If `IsTruncated` is false, this response includes the last hosted zone that is associated with the current account.
     *   The `NextDNSName` element and `NextHostedZoneId` elements are omitted from the response.
     * - The `NextDNSName` and `NextHostedZoneId` elements in the response contain the domain name and the hosted zone ID of
     *   the next hosted zone that is associated with the current Amazon Web Services account. If you want to list more
     *   hosted zones, make another call to `ListHostedZonesByName`, and specify the value of `NextDNSName` and
     *   `NextHostedZoneId` in the `dnsname` and `hostedzoneid` parameters, respectively.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/DomainNameFormat.html
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListHostedZonesByName.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#listhostedzonesbyname
     *
     * @param array{
     *   DNSName?: string|null,
     *   HostedZoneId?: string|null,
     *   MaxItems?: string|null,
     *   '@region'?: string|null,
     * }|ListHostedZonesByNameRequest $input
     *
     * @throws InvalidDomainNameException
     * @throws InvalidInputException
     */
    public function listHostedZonesByName($input = []): ListHostedZonesByNameResponse
    {
        $input = ListHostedZonesByNameRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListHostedZonesByName', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidDomainName' => InvalidDomainNameException::class,
            'InvalidInput' => InvalidInputException::class,
        ]]));

        return new ListHostedZonesByNameResponse($response);
    }

    /**
     * Lists the resource record sets in a specified hosted zone.
     *
     * `ListResourceRecordSets` returns up to 300 resource record sets at a time in ASCII order, beginning at a position
     * specified by the `name` and `type` elements.
     *
     * **Sort order**
     *
     * `ListResourceRecordSets` sorts results first by DNS name with the labels reversed, for example:
     *
     * `com.example.www.`
     *
     * Note the trailing dot, which can change the sort order when the record name contains characters that appear before
     * `.` (decimal 46) in the ASCII table. These characters include the following: `! " # $ % & ' ( ) * + , -`
     *
     * When multiple records have the same DNS name, `ListResourceRecordSets` sorts results by the record type.
     *
     * **Specifying where to start listing records**
     *
     * You can use the name and type elements to specify the resource record set that the list begins with:
     *
     * - `If you do not specify Name or Type`:
     *
     *   The results begin with the first resource record set that the hosted zone contains.
     * - `If you specify Name but not Type`:
     *
     *   The results begin with the first resource record set in the list whose name is greater than or equal to `Name`.
     * - `If you specify Type but not Name`:
     *
     *   Amazon Route 53 returns the `InvalidInput` error.
     * - `If you specify both Name and Type`:
     *
     *   The results begin with the first resource record set in the list whose name is greater than or equal to `Name`, and
     *   whose type is greater than or equal to `Type`.
     *
     *   > Type is only used to sort between records with the same record Name.
     *
     *
     * **Resource record sets that are PENDING**
     *
     * This action returns the most current version of the records. This includes records that are `PENDING`, and that are
     * not yet available on all Route 53 DNS servers.
     *
     * **Changing resource record sets**
     *
     * To ensure that you get an accurate listing of the resource record sets for a hosted zone at a point in time, do not
     * submit a `ChangeResourceRecordSets` request while you're paging through the results of a `ListResourceRecordSets`
     * request. If you do, some pages may display results without the latest changes while other pages display results with
     * the latest changes.
     *
     * **Displaying the next page of results**
     *
     * If a `ListResourceRecordSets` command returns more than one page of results, the value of `IsTruncated` is `true`. To
     * display the next page of results, get the values of `NextRecordName`, `NextRecordType`, and `NextRecordIdentifier`
     * (if any) from the response. Then submit another `ListResourceRecordSets` request, and specify those values for
     * `StartRecordName`, `StartRecordType`, and `StartRecordIdentifier`.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_ListResourceRecordSets.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-route53-2013-04-01.html#listresourcerecordsets
     *
     * @param array{
     *   HostedZoneId: string,
     *   StartRecordName?: string|null,
     *   StartRecordType?: RRType::*|null,
     *   StartRecordIdentifier?: string|null,
     *   MaxItems?: string|null,
     *   '@region'?: string|null,
     * }|ListResourceRecordSetsRequest $input
     *
     * @throws InvalidInputException
     * @throws NoSuchHostedZoneException
     */
    public function listResourceRecordSets($input): ListResourceRecordSetsResponse
    {
        $input = ListResourceRecordSetsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListResourceRecordSets', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInput' => InvalidInputException::class,
            'NoSuchHostedZone' => NoSuchHostedZoneException::class,
        ]]));

        return new ListResourceRecordSetsResponse($response, $this, $input);
    }

    /**
     * @see getChange
     *
     * @param array{
     *   Id: string,
     *   '@region'?: string|null,
     * }|GetChangeRequest $input
     */
    public function resourceRecordSetsChanged($input): ResourceRecordSetsChangedWaiter
    {
        $input = GetChangeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetChange', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInput' => InvalidInputException::class,
            'NoSuchChange' => NoSuchChangeException::class,
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
            case 'fips-aws-us-gov-global':
                return [
                    'endpoint' => 'https://route53.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
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
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://route53.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
            case 'us-isob-west-1':
                return [
                    'endpoint' => 'https://route53.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'route53',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => 'https://route53.csp.hci.ic.gov',
                    'signRegion' => 'us-isof-south-1',
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
