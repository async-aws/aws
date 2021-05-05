<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Route53\Enum\ResourceRecordSetFailover;
use AsyncAws\Route53\Enum\ResourceRecordSetRegion;
use AsyncAws\Route53\Enum\RRType;

/**
 * Information about the resource record set to create, delete, or update.
 */
final class ResourceRecordSet
{
    /**
     * For `ChangeResourceRecordSets` requests, the name of the record that you want to create, update, or delete. For
     * `ListResourceRecordSets` responses, the name of a record in the specified hosted zone.
     */
    private $name;

    /**
     * The DNS record type. For information about different record types and how data is encoded for them, see Supported DNS
     * Resource Record Types in the *Amazon Route 53 Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/ResourceRecordTypes.html
     */
    private $type;

    /**
     * *Resource record sets that have a routing policy other than simple:* An identifier that differentiates among multiple
     * resource record sets that have the same combination of name and type, such as multiple weighted resource record sets
     * named acme.example.com that have a type of A. In a group of resource record sets that have the same name and type,
     * the value of `SetIdentifier` must be unique for each resource record set.
     */
    private $setIdentifier;

    /**
     * *Weighted resource record sets only:* Among resource record sets that have the same combination of DNS name and type,
     * a value that determines the proportion of DNS queries that Amazon Route 53 responds to using the current resource
     * record set. Route 53 calculates the sum of the weights for the resource record sets that have the same combination of
     * DNS name and type. Route 53 then responds to queries based on the ratio of a resource's weight to the total. Note the
     * following:.
     */
    private $weight;

    /**
     * *Latency-based resource record sets only:* The Amazon EC2 Region where you created the resource that this resource
     * record set refers to. The resource typically is an AWS resource, such as an EC2 instance or an ELB load balancer, and
     * is referred to by an IP address or a DNS domain name, depending on the record type.
     */
    private $region;

    /**
     * *Geolocation resource record sets only:* A complex type that lets you control how Amazon Route 53 responds to DNS
     * queries based on the geographic origin of the query. For example, if you want all queries from Africa to be routed to
     * a web server with an IP address of `192.0.2.111`, create a resource record set with a `Type` of `A` and a
     * `ContinentCode` of `AF`.
     */
    private $geoLocation;

    /**
     * *Failover resource record sets only:* To configure failover, you add the `Failover` element to two resource record
     * sets. For one resource record set, you specify `PRIMARY` as the value for `Failover`; for the other resource record
     * set, you specify `SECONDARY`. In addition, you include the `HealthCheckId` element and specify the health check that
     * you want Amazon Route 53 to perform for each resource record set.
     */
    private $failover;

    /**
     * *Multivalue answer resource record sets only*: To route traffic approximately randomly to multiple resources, such as
     * web servers, create one multivalue answer record for each resource and specify `true` for `MultiValueAnswer`. Note
     * the following:.
     */
    private $multiValueAnswer;

    /**
     * The resource record cache time to live (TTL), in seconds. Note the following:.
     */
    private $ttl;

    /**
     * Information about the resource records to act upon.
     */
    private $resourceRecords;

    /**
     * *Alias resource record sets only:* Information about the AWS resource, such as a CloudFront distribution or an Amazon
     * S3 bucket, that you want to route traffic to.
     */
    private $aliasTarget;

    /**
     * If you want Amazon Route 53 to return this resource record set in response to a DNS query only when the status of a
     * health check is healthy, include the `HealthCheckId` element and specify the ID of the applicable health check.
     */
    private $healthCheckId;

    /**
     * When you create a traffic policy instance, Amazon Route 53 automatically creates a resource record set.
     * `TrafficPolicyInstanceId` is the ID of the traffic policy instance that Route 53 created this resource record set
     * for.
     */
    private $trafficPolicyInstanceId;

    /**
     * @param array{
     *   Name: string,
     *   Type: RRType::*,
     *   SetIdentifier?: null|string,
     *   Weight?: null|string,
     *   Region?: null|ResourceRecordSetRegion::*,
     *   GeoLocation?: null|GeoLocation|array,
     *   Failover?: null|ResourceRecordSetFailover::*,
     *   MultiValueAnswer?: null|bool,
     *   TTL?: null|string,
     *   ResourceRecords?: null|ResourceRecord[],
     *   AliasTarget?: null|AliasTarget|array,
     *   HealthCheckId?: null|string,
     *   TrafficPolicyInstanceId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->type = $input['Type'] ?? null;
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
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAliasTarget(): ?AliasTarget
    {
        return $this->aliasTarget;
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

    public function getTtl(): ?string
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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('Name', $v));
        if (null === $v = $this->type) {
            throw new InvalidArgument(sprintf('Missing parameter "Type" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!RRType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "RRType".', __CLASS__, $v));
        }
        $node->appendChild($document->createElement('Type', $v));
        if (null !== $v = $this->setIdentifier) {
            $node->appendChild($document->createElement('SetIdentifier', $v));
        }
        if (null !== $v = $this->weight) {
            $node->appendChild($document->createElement('Weight', $v));
        }
        if (null !== $v = $this->region) {
            if (!ResourceRecordSetRegion::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Region" for "%s". The value "%s" is not a valid "ResourceRecordSetRegion".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Region', $v));
        }
        if (null !== $v = $this->geoLocation) {
            $node->appendChild($child = $document->createElement('GeoLocation'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->failover) {
            if (!ResourceRecordSetFailover::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Failover" for "%s". The value "%s" is not a valid "ResourceRecordSetFailover".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Failover', $v));
        }
        if (null !== $v = $this->multiValueAnswer) {
            $node->appendChild($document->createElement('MultiValueAnswer', $v ? 'true' : 'false'));
        }
        if (null !== $v = $this->ttl) {
            $node->appendChild($document->createElement('TTL', $v));
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
    }
}
