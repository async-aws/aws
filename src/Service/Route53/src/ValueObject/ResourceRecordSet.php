<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Route53\Enum\ResourceRecordSetFailover;
use AsyncAws\Route53\Enum\ResourceRecordSetRegion;
use AsyncAws\Route53\Enum\RRType;

/**
 * Information about the resource record set to create or delete.
 */
final class ResourceRecordSet
{
    /**
     * For `ChangeResourceRecordSets` requests, the name of the record that you want to create, update, or delete. For
     * `ListResourceRecordSets` responses, the name of a record in the specified hosted zone.
     *
     * **ChangeResourceRecordSets Only**
     *
     * Enter a fully qualified domain name, for example, `www.example.com`. You can optionally include a trailing dot. If
     * you omit the trailing dot, Amazon Route 53 assumes that the domain name that you specify is fully qualified. This
     * means that Route 53 treats `www.example.com` (without a trailing dot) and `www.example.com.` (with a trailing dot) as
     * identical.
     *
     * For information about how to specify characters other than `a-z`, `0-9`, and `-` (hyphen) and how to specify
     * internationalized domain names, see DNS Domain Name Format [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * You can use the asterisk (*) wildcard to replace the leftmost label in a domain name, for example, `*.example.com`.
     * Note the following:
     *
     * - The * must replace the entire label. For example, you can't specify `*prod.example.com` or `prod*.example.com`.
     * - The * can't replace any of the middle labels, for example, marketing.*.example.com.
     * - If you include * in any position other than the leftmost label in a domain name, DNS treats it as an * character
     *   (ASCII 42), not as a wildcard.
     *
     *   ! You can't use the * wildcard for resource records sets that have a type of NS.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/DomainNameFormat.html
     *
     * @var string
     */
    private $name;

    /**
     * The DNS record type. For information about different record types and how data is encoded for them, see Supported DNS
     * Resource Record Types [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * Valid values for basic resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `DS` |`MX` | `NAPTR` | `NS` | `PTR` |
     * `SOA` | `SPF` | `SRV` | `TXT`| `TLSA`| `SSHFP`| `SVCB`| `HTTPS`
     *
     * Values for weighted, latency, geolocation, and failover resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `MX` |
     * `NAPTR` | `PTR` | `SPF` | `SRV` | `TXT`| `TLSA`| `SSHFP`| `SVCB`| `HTTPS`. When creating a group of weighted,
     * latency, geolocation, or failover resource record sets, specify the same value for all of the resource record sets in
     * the group.
     *
     * Valid values for multivalue answer resource record sets: `A` | `AAAA` | `MX` | `NAPTR` | `PTR` | `SPF` | `SRV` |
     * `TXT`| `CAA`| `TLSA`| `SSHFP`| `SVCB`| `HTTPS`
     *
     * > SPF records were formerly used to verify the identity of the sender of email messages. However, we no longer
     * > recommend that you create resource record sets for which the value of `Type` is `SPF`. RFC 7208, *Sender Policy
     * > Framework (SPF) for Authorizing Use of Domains in Email, Version 1*, has been updated to say, "...[I]ts existence
     * > and mechanism defined in [RFC4408] have led to some interoperability issues. Accordingly, its use is no longer
     * > appropriate for SPF version 1; implementations are not to use it." In RFC 7208, see section 14.1, The SPF DNS
     * > Record Type [^2].
     *
     * Values for alias resource record sets:
     *
     * - **Amazon API Gateway custom regional APIs and edge-optimized APIs:**`A`
     * - **CloudFront distributions:**`A`
     *
     *   If IPv6 is enabled for the distribution, create two resource record sets to route traffic to your distribution, one
     *   with a value of `A` and one with a value of `AAAA`.
     * - **Amazon API Gateway environment that has a regionalized subdomain**: `A`
     * - **ELB load balancers:**`A` | `AAAA`
     * - **Amazon S3 buckets:**`A`
     * - **Amazon Virtual Private Cloud interface VPC endpoints**`A`
     * - **Another resource record set in this hosted zone:** Specify the type of the resource record set that you're
     *   creating the alias for. All values are supported except `NS` and `SOA`.
     *
     *   > If you're creating an alias record that has the same name as the hosted zone (known as the zone apex), you can't
     *   > route traffic to a record for which the value of `Type` is `CNAME`. This is because the alias record must have
     *   > the same type as the record you're routing traffic to, and creating a CNAME record for the zone apex isn't
     *   > supported even for an alias record.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/ResourceRecordTypes.html
     * [^2]: http://tools.ietf.org/html/rfc7208#section-14.1
     *
     * @var RRType::*
     */
    private $type;

    /**
     * *Resource record sets that have a routing policy other than simple:* An identifier that differentiates among multiple
     * resource record sets that have the same combination of name and type, such as multiple weighted resource record sets
     * named acme.example.com that have a type of A. In a group of resource record sets that have the same name and type,
     * the value of `SetIdentifier` must be unique for each resource record set.
     *
     * For information about routing policies, see Choosing a Routing Policy [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/routing-policy.html
     *
     * @var string|null
     */
    private $setIdentifier;

    /**
     * *Weighted resource record sets only:* Among resource record sets that have the same combination of DNS name and type,
     * a value that determines the proportion of DNS queries that Amazon Route 53 responds to using the current resource
     * record set. Route 53 calculates the sum of the weights for the resource record sets that have the same combination of
     * DNS name and type. Route 53 then responds to queries based on the ratio of a resource's weight to the total. Note the
     * following:
     *
     * - You must specify a value for the `Weight` element for every weighted resource record set.
     * - You can only specify one `ResourceRecord` per weighted resource record set.
     * - You can't create latency, failover, or geolocation resource record sets that have the same values for the `Name`
     *   and `Type` elements as weighted resource record sets.
     * - You can create a maximum of 100 weighted resource record sets that have the same values for the `Name` and `Type`
     *   elements.
     * - For weighted (but not weighted alias) resource record sets, if you set `Weight` to `0` for a resource record set,
     *   Route 53 never responds to queries with the applicable value for that resource record set. However, if you set
     *   `Weight` to `0` for all resource record sets that have the same combination of DNS name and type, traffic is routed
     *   to all resources with equal probability.
     *
     *   The effect of setting `Weight` to `0` is different when you associate health checks with weighted resource record
     *   sets. For more information, see Options for Configuring Route 53 Active-Active and Active-Passive Failover [^1] in
     *   the *Amazon Route 53 Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-configuring-options.html
     *
     * @var int|null
     */
    private $weight;

    /**
     * *Latency-based resource record sets only:* The Amazon EC2 Region where you created the resource that this resource
     * record set refers to. The resource typically is an Amazon Web Services resource, such as an EC2 instance or an ELB
     * load balancer, and is referred to by an IP address or a DNS domain name, depending on the record type.
     *
     * When Amazon Route 53 receives a DNS query for a domain name and type for which you have created latency resource
     * record sets, Route 53 selects the latency resource record set that has the lowest latency between the end user and
     * the associated Amazon EC2 Region. Route 53 then returns the value that is associated with the selected resource
     * record set.
     *
     * Note the following:
     *
     * - You can only specify one `ResourceRecord` per latency resource record set.
     * - You can only create one latency resource record set for each Amazon EC2 Region.
     * - You aren't required to create latency resource record sets for all Amazon EC2 Regions. Route 53 will choose the
     *   region with the best latency from among the regions that you create latency resource record sets for.
     * - You can't create non-latency resource record sets that have the same values for the `Name` and `Type` elements as
     *   latency resource record sets.
     *
     * @var ResourceRecordSetRegion::*|null
     */
    private $region;

    /**
     * *Geolocation resource record sets only:* A complex type that lets you control how Amazon Route 53 responds to DNS
     * queries based on the geographic origin of the query. For example, if you want all queries from Africa to be routed to
     * a web server with an IP address of `192.0.2.111`, create a resource record set with a `Type` of `A` and a
     * `ContinentCode` of `AF`.
     *
     * If you create separate resource record sets for overlapping geographic regions (for example, one resource record set
     * for a continent and one for a country on the same continent), priority goes to the smallest geographic region. This
     * allows you to route most queries for a continent to one resource and to route queries for a country on that continent
     * to a different resource.
     *
     * You can't create two geolocation resource record sets that specify the same geographic location.
     *
     * The value `*` in the `CountryCode` element matches all geographic locations that aren't specified in other
     * geolocation resource record sets that have the same values for the `Name` and `Type` elements.
     *
     * ! Geolocation works by mapping IP addresses to locations. However, some IP addresses aren't mapped to geographic
     * ! locations, so even if you create geolocation resource record sets that cover all seven continents, Route 53 will
     * ! receive some DNS queries from locations that it can't identify. We recommend that you create a resource record set
     * ! for which the value of `CountryCode` is `*`. Two groups of queries are routed to the resource that you specify in
     * ! this record: queries that come from locations for which you haven't created geolocation resource record sets and
     * ! queries from IP addresses that aren't mapped to a location. If you don't create a `*` resource record set, Route 53
     * ! returns a "no answer" response for queries from those locations.
     *
     * You can't create non-geolocation resource record sets that have the same values for the `Name` and `Type` elements as
     * geolocation resource record sets.
     *
     * @var GeoLocation|null
     */
    private $geoLocation;

    /**
     * *Failover resource record sets only:* To configure failover, you add the `Failover` element to two resource record
     * sets. For one resource record set, you specify `PRIMARY` as the value for `Failover`; for the other resource record
     * set, you specify `SECONDARY`. In addition, you include the `HealthCheckId` element and specify the health check that
     * you want Amazon Route 53 to perform for each resource record set.
     *
     * Except where noted, the following failover behaviors assume that you have included the `HealthCheckId` element in
     * both resource record sets:
     *
     * - When the primary resource record set is healthy, Route 53 responds to DNS queries with the applicable value from
     *   the primary resource record set regardless of the health of the secondary resource record set.
     * - When the primary resource record set is unhealthy and the secondary resource record set is healthy, Route 53
     *   responds to DNS queries with the applicable value from the secondary resource record set.
     * - When the secondary resource record set is unhealthy, Route 53 responds to DNS queries with the applicable value
     *   from the primary resource record set regardless of the health of the primary resource record set.
     * - If you omit the `HealthCheckId` element for the secondary resource record set, and if the primary resource record
     *   set is unhealthy, Route 53 always responds to DNS queries with the applicable value from the secondary resource
     *   record set. This is true regardless of the health of the associated endpoint.
     *
     * You can't create non-failover resource record sets that have the same values for the `Name` and `Type` elements as
     * failover resource record sets.
     *
     * For failover alias resource record sets, you must also include the `EvaluateTargetHealth` element and set the value
     * to true.
     *
     * For more information about configuring failover for Route 53, see the following topics in the *Amazon Route 53
     * Developer Guide*:
     *
     * - Route 53 Health Checks and DNS Failover [^1]
     * - Configuring Failover in a Private Hosted Zone [^2]
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     * [^2]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-private-hosted-zones.html
     *
     * @var ResourceRecordSetFailover::*|null
     */
    private $failover;

    /**
     * *Multivalue answer resource record sets only*: To route traffic approximately randomly to multiple resources, such as
     * web servers, create one multivalue answer record for each resource and specify `true` for `MultiValueAnswer`. Note
     * the following:
     *
     * - If you associate a health check with a multivalue answer resource record set, Amazon Route 53 responds to DNS
     *   queries with the corresponding IP address only when the health check is healthy.
     * - If you don't associate a health check with a multivalue answer record, Route 53 always considers the record to be
     *   healthy.
     * - Route 53 responds to DNS queries with up to eight healthy records; if you have eight or fewer healthy records,
     *   Route 53 responds to all DNS queries with all the healthy records.
     * - If you have more than eight healthy records, Route 53 responds to different DNS resolvers with different
     *   combinations of healthy records.
     * - When all records are unhealthy, Route 53 responds to DNS queries with up to eight unhealthy records.
     * - If a resource becomes unavailable after a resolver caches a response, client software typically tries another of
     *   the IP addresses in the response.
     *
     * You can't create multivalue answer alias records.
     *
     * @var bool|null
     */
    private $multiValueAnswer;

    /**
     * The resource record cache time to live (TTL), in seconds. Note the following:
     *
     * - If you're creating or updating an alias resource record set, omit `TTL`. Amazon Route 53 uses the value of `TTL`
     *   for the alias target.
     * - If you're associating this resource record set with a health check (if you're adding a `HealthCheckId` element), we
     *   recommend that you specify a `TTL` of 60 seconds or less so clients respond quickly to changes in health status.
     * - All of the resource record sets in a group of weighted resource record sets must have the same value for `TTL`.
     * - If a group of weighted resource record sets includes one or more weighted alias resource record sets for which the
     *   alias target is an ELB load balancer, we recommend that you specify a `TTL` of 60 seconds for all of the non-alias
     *   weighted resource record sets that have the same name and type. Values other than 60 seconds (the TTL for load
     *   balancers) will change the effect of the values that you specify for `Weight`.
     *
     * @var int|null
     */
    private $ttl;

    /**
     * Information about the resource records to act upon.
     *
     * > If you're creating an alias resource record set, omit `ResourceRecords`.
     *
     * @var ResourceRecord[]|null
     */
    private $resourceRecords;

    /**
     * *Alias resource record sets only:* Information about the Amazon Web Services resource, such as a CloudFront
     * distribution or an Amazon S3 bucket, that you want to route traffic to.
     *
     * If you're creating resource records sets for a private hosted zone, note the following:
     *
     * - You can't create an alias resource record set in a private hosted zone to route traffic to a CloudFront
     *   distribution.
     * - For information about creating failover resource record sets in a private hosted zone, see Configuring Failover in
     *   a Private Hosted Zone [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-private-hosted-zones.html
     *
     * @var AliasTarget|null
     */
    private $aliasTarget;

    /**
     * If you want Amazon Route 53 to return this resource record set in response to a DNS query only when the status of a
     * health check is healthy, include the `HealthCheckId` element and specify the ID of the applicable health check.
     *
     * Route 53 determines whether a resource record set is healthy based on one of the following:
     *
     * - By periodically sending a request to the endpoint that is specified in the health check
     * - By aggregating the status of a specified group of health checks (calculated health checks)
     * - By determining the current state of a CloudWatch alarm (CloudWatch metric health checks)
     *
     * ! Route 53 doesn't check the health of the endpoint that is specified in the resource record set, for example, the
     * ! endpoint specified by the IP address in the `Value` element. When you add a `HealthCheckId` element to a resource
     * ! record set, Route 53 checks the health of the endpoint that you specified in the health check.
     *
     * For more information, see the following topics in the *Amazon Route 53 Developer Guide*:
     *
     * - How Amazon Route 53 Determines Whether an Endpoint Is Healthy [^1]
     * - Route 53 Health Checks and DNS Failover [^2]
     * - Configuring Failover in a Private Hosted Zone [^3]
     *
     * **When to Specify HealthCheckId**
     *
     * Specifying a value for `HealthCheckId` is useful only when Route 53 is choosing between two or more resource record
     * sets to respond to a DNS query, and you want Route 53 to base the choice in part on the status of a health check.
     * Configuring health checks makes sense only in the following configurations:
     *
     * - **Non-alias resource record sets**: You're checking the health of a group of non-alias resource record sets that
     *   have the same routing policy, name, and type (such as multiple weighted records named www.example.com with a type
     *   of A) and you specify health check IDs for all the resource record sets.
     *
     *   If the health check status for a resource record set is healthy, Route 53 includes the record among the records
     *   that it responds to DNS queries with.
     *
     *   If the health check status for a resource record set is unhealthy, Route 53 stops responding to DNS queries using
     *   the value for that resource record set.
     *
     *   If the health check status for all resource record sets in the group is unhealthy, Route 53 considers all resource
     *   record sets in the group healthy and responds to DNS queries accordingly.
     * - **Alias resource record sets**: You specify the following settings:
     *
     *   - You set `EvaluateTargetHealth` to true for an alias resource record set in a group of resource record sets that
     *     have the same routing policy, name, and type (such as multiple weighted records named www.example.com with a type
     *     of A).
     *   - You configure the alias resource record set to route traffic to a non-alias resource record set in the same
     *     hosted zone.
     *   - You specify a health check ID for the non-alias resource record set.
     *
     *   If the health check status is healthy, Route 53 considers the alias resource record set to be healthy and includes
     *   the alias record among the records that it responds to DNS queries with.
     *
     *   If the health check status is unhealthy, Route 53 stops responding to DNS queries using the alias resource record
     *   set.
     *
     *   > The alias resource record set can also route traffic to a *group* of non-alias resource record sets that have the
     *   > same routing policy, name, and type. In that configuration, associate health checks with all of the resource
     *   > record sets in the group of non-alias resource record sets.
     *
     *
     * **Geolocation Routing**
     *
     * For geolocation resource record sets, if an endpoint is unhealthy, Route 53 looks for a resource record set for the
     * larger, associated geographic region. For example, suppose you have resource record sets for a state in the United
     * States, for the entire United States, for North America, and a resource record set that has `*` for `CountryCode` is
     * `*`, which applies to all locations. If the endpoint for the state resource record set is unhealthy, Route 53 checks
     * for healthy resource record sets in the following order until it finds a resource record set for which the endpoint
     * is healthy:
     *
     * - The United States
     * - North America
     * - The default resource record set
     *
     * **Specifying the Health Check Endpoint by Domain Name**
     *
     * If your health checks specify the endpoint only by domain name, we recommend that you create a separate health check
     * for each endpoint. For example, create a health check for each `HTTP` server that is serving content for
     * `www.example.com`. For the value of `FullyQualifiedDomainName`, specify the domain name of the server (such as
     * `us-east-2-www.example.com`), not the name of the resource record sets (`www.example.com`).
     *
     * ! Health check results will be unpredictable if you do the following:
     * !
     * ! - Create a health check that has the same value for `FullyQualifiedDomainName` as the name of a resource record
     * !   set.
     * ! - Associate that health check with the resource record set.
     * !
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-determining-health-of-endpoints.html
     * [^2]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover.html
     * [^3]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-private-hosted-zones.html
     *
     * @var string|null
     */
    private $healthCheckId;

    /**
     * When you create a traffic policy instance, Amazon Route 53 automatically creates a resource record set.
     * `TrafficPolicyInstanceId` is the ID of the traffic policy instance that Route 53 created this resource record set
     * for.
     *
     * ! To delete the resource record set that is associated with a traffic policy instance, use
     * ! `DeleteTrafficPolicyInstance`. Route 53 will delete the resource record set automatically. If you delete the
     * ! resource record set by using `ChangeResourceRecordSets`, Route 53 doesn't automatically delete the traffic policy
     * ! instance, and you'll continue to be charged for it even though it's no longer in use.
     *
     * @var string|null
     */
    private $trafficPolicyInstanceId;

    /**
     * @var CidrRoutingConfig|null
     */
    private $cidrRoutingConfig;

    /**
     * * GeoproximityLocation resource record sets only:* A complex type that lets you control how Route 53 responds to DNS
     * queries based on the geographic origin of the query and your resources.
     *
     * @var GeoProximityLocation|null
     */
    private $geoProximityLocation;

    /**
     * @param array{
     *   Name: string,
     *   Type: RRType::*,
     *   SetIdentifier?: string|null,
     *   Weight?: int|null,
     *   Region?: ResourceRecordSetRegion::*|null,
     *   GeoLocation?: GeoLocation|array|null,
     *   Failover?: ResourceRecordSetFailover::*|null,
     *   MultiValueAnswer?: bool|null,
     *   TTL?: int|null,
     *   ResourceRecords?: array<ResourceRecord|array>|null,
     *   AliasTarget?: AliasTarget|array|null,
     *   HealthCheckId?: string|null,
     *   TrafficPolicyInstanceId?: string|null,
     *   CidrRoutingConfig?: CidrRoutingConfig|array|null,
     *   GeoProximityLocation?: GeoProximityLocation|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
        $this->setIdentifier = $input['SetIdentifier'] ?? null;
        $this->weight = $input['Weight'] ?? null;
        $this->region = $input['Region'] ?? null;
        $this->geoLocation = isset($input['GeoLocation']) ? GeoLocation::create($input['GeoLocation']) : null;
        $this->failover = $input['Failover'] ?? null;
        $this->multiValueAnswer = $input['MultiValueAnswer'] ?? null;
        $this->ttl = $input['TTL'] ?? null;
        $this->resourceRecords = isset($input['ResourceRecords']) ? array_map([ResourceRecord::class, 'create'], $input['ResourceRecords']) : null;
        $this->aliasTarget = isset($input['AliasTarget']) ? AliasTarget::create($input['AliasTarget']) : null;
        $this->healthCheckId = $input['HealthCheckId'] ?? null;
        $this->trafficPolicyInstanceId = $input['TrafficPolicyInstanceId'] ?? null;
        $this->cidrRoutingConfig = isset($input['CidrRoutingConfig']) ? CidrRoutingConfig::create($input['CidrRoutingConfig']) : null;
        $this->geoProximityLocation = isset($input['GeoProximityLocation']) ? GeoProximityLocation::create($input['GeoProximityLocation']) : null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Type: RRType::*,
     *   SetIdentifier?: string|null,
     *   Weight?: int|null,
     *   Region?: ResourceRecordSetRegion::*|null,
     *   GeoLocation?: GeoLocation|array|null,
     *   Failover?: ResourceRecordSetFailover::*|null,
     *   MultiValueAnswer?: bool|null,
     *   TTL?: int|null,
     *   ResourceRecords?: array<ResourceRecord|array>|null,
     *   AliasTarget?: AliasTarget|array|null,
     *   HealthCheckId?: string|null,
     *   TrafficPolicyInstanceId?: string|null,
     *   CidrRoutingConfig?: CidrRoutingConfig|array|null,
     *   GeoProximityLocation?: GeoProximityLocation|array|null,
     * }|ResourceRecordSet $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAliasTarget(): ?AliasTarget
    {
        return $this->aliasTarget;
    }

    public function getCidrRoutingConfig(): ?CidrRoutingConfig
    {
        return $this->cidrRoutingConfig;
    }

    /**
     * @return ResourceRecordSetFailover::*|null
     */
    public function getFailover(): ?string
    {
        return $this->failover;
    }

    public function getGeoLocation(): ?GeoLocation
    {
        return $this->geoLocation;
    }

    public function getGeoProximityLocation(): ?GeoProximityLocation
    {
        return $this->geoProximityLocation;
    }

    public function getHealthCheckId(): ?string
    {
        return $this->healthCheckId;
    }

    public function getMultiValueAnswer(): ?bool
    {
        return $this->multiValueAnswer;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ResourceRecordSetRegion::*|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return ResourceRecord[]
     */
    public function getResourceRecords(): array
    {
        return $this->resourceRecords ?? [];
    }

    public function getSetIdentifier(): ?string
    {
        return $this->setIdentifier;
    }

    public function getTrafficPolicyInstanceId(): ?string
    {
        return $this->trafficPolicyInstanceId;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    /**
     * @return RRType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->name;
        $node->appendChild($document->createElement('Name', $v));
        $v = $this->type;
        if (!RRType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "RRType".', __CLASS__, $v));
        }
        $node->appendChild($document->createElement('Type', $v));
        if (null !== $v = $this->setIdentifier) {
            $node->appendChild($document->createElement('SetIdentifier', $v));
        }
        if (null !== $v = $this->weight) {
            $node->appendChild($document->createElement('Weight', (string) $v));
        }
        if (null !== $v = $this->region) {
            if (!ResourceRecordSetRegion::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Region" for "%s". The value "%s" is not a valid "ResourceRecordSetRegion".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Region', $v));
        }
        if (null !== $v = $this->geoLocation) {
            $node->appendChild($child = $document->createElement('GeoLocation'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->failover) {
            if (!ResourceRecordSetFailover::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Failover" for "%s". The value "%s" is not a valid "ResourceRecordSetFailover".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Failover', $v));
        }
        if (null !== $v = $this->multiValueAnswer) {
            $node->appendChild($document->createElement('MultiValueAnswer', $v ? 'true' : 'false'));
        }
        if (null !== $v = $this->ttl) {
            $node->appendChild($document->createElement('TTL', (string) $v));
        }
        if (null !== $v = $this->resourceRecords) {
            $node->appendChild($nodeList = $document->createElement('ResourceRecords'));
            foreach ($v as $item) {
                $nodeList->appendChild($child = $document->createElement('ResourceRecord'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->aliasTarget) {
            $node->appendChild($child = $document->createElement('AliasTarget'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->healthCheckId) {
            $node->appendChild($document->createElement('HealthCheckId', $v));
        }
        if (null !== $v = $this->trafficPolicyInstanceId) {
            $node->appendChild($document->createElement('TrafficPolicyInstanceId', $v));
        }
        if (null !== $v = $this->cidrRoutingConfig) {
            $node->appendChild($child = $document->createElement('CidrRoutingConfig'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->geoProximityLocation) {
            $node->appendChild($child = $document->createElement('GeoProximityLocation'));

            $v->requestBody($child, $document);
        }
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
