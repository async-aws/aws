<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Route53\Enum\RRType;

/**
 * A request for the resource record sets that are associated with a specified hosted zone.
 */
final class ListResourceRecordSetsRequest extends Input
{
    /**
     * The ID of the hosted zone that contains the resource record sets that you want to list.
     *
     * @required
     *
     * @var string|null
     */
    private $hostedZoneId;

    /**
     * The first name in the lexicographic ordering of resource record sets that you want to list. If the specified record
     * name doesn't exist, the results begin with the first resource record set that has a name greater than the value of
     * `name`.
     *
     * @var string|null
     */
    private $startRecordName;

    /**
     * The type of resource record set to begin the record listing from.
     *
     * Valid values for basic resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `MX` | `NAPTR` | `NS` | `PTR` | `SOA` |
     * `SPF` | `SRV` | `TXT`
     *
     * Values for weighted, latency, geolocation, and failover resource record sets: `A` | `AAAA` | `CAA` | `CNAME` | `MX` |
     * `NAPTR` | `PTR` | `SPF` | `SRV` | `TXT`
     *
     * Values for alias resource record sets:
     *
     * - **API Gateway custom regional API or edge-optimized API**: A
     * - **CloudFront distribution**: A or AAAA
     * - **Elastic Beanstalk environment that has a regionalized subdomain**: A
     * - **Elastic Load Balancing load balancer**: A | AAAA
     * - **S3 bucket**: A
     * - **VPC interface VPC endpoint**: A
     * - **Another resource record set in this hosted zone:** The type of the resource record set that the alias references.
     *
     * Constraint: Specifying `type` without specifying `name` returns an `InvalidInput` error.
     *
     * @var RRType::*|null
     */
    private $startRecordType;

    /**
     * *Resource record sets that have a routing policy other than simple:* If results were truncated for a given DNS name
     * and type, specify the value of `NextRecordIdentifier` from the previous response to get the next resource record set
     * that has the current DNS name and type.
     *
     * @var string|null
     */
    private $startRecordIdentifier;

    /**
     * (Optional) The maximum number of resource records sets to include in the response body for this request. If the
     * response includes more than `maxitems` resource record sets, the value of the `IsTruncated` element in the response
     * is `true`, and the values of the `NextRecordName` and `NextRecordType` elements in the response identify the first
     * resource record set in the next group of `maxitems` resource record sets.
     *
     * @var string|null
     */
    private $maxItems;

    /**
     * @param array{
     *   HostedZoneId?: string,
     *   StartRecordName?: string|null,
     *   StartRecordType?: RRType::*|null,
     *   StartRecordIdentifier?: string|null,
     *   MaxItems?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->hostedZoneId = $input['HostedZoneId'] ?? null;
        $this->startRecordName = $input['StartRecordName'] ?? null;
        $this->startRecordType = $input['StartRecordType'] ?? null;
        $this->startRecordIdentifier = $input['StartRecordIdentifier'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   HostedZoneId?: string,
     *   StartRecordName?: string|null,
     *   StartRecordType?: RRType::*|null,
     *   StartRecordIdentifier?: string|null,
     *   MaxItems?: string|null,
     *   '@region'?: string|null,
     * }|ListResourceRecordSetsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHostedZoneId(): ?string
    {
        return $this->hostedZoneId;
    }

    public function getMaxItems(): ?string
    {
        return $this->maxItems;
    }

    public function getStartRecordIdentifier(): ?string
    {
        return $this->startRecordIdentifier;
    }

    public function getStartRecordName(): ?string
    {
        return $this->startRecordName;
    }

    /**
     * @return RRType::*|null
     */
    public function getStartRecordType(): ?string
    {
        return $this->startRecordType;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];

        // Prepare query
        $query = [];
        if (null !== $this->startRecordName) {
            $query['name'] = $this->startRecordName;
        }
        if (null !== $this->startRecordType) {
            if (!RRType::exists($this->startRecordType)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "StartRecordType" for "%s". The value "%s" is not a valid "RRType".', __CLASS__, $this->startRecordType));
            }
            $query['type'] = $this->startRecordType;
        }
        if (null !== $this->startRecordIdentifier) {
            $query['identifier'] = $this->startRecordIdentifier;
        }
        if (null !== $this->maxItems) {
            $query['maxitems'] = $this->maxItems;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->hostedZoneId) {
            throw new InvalidArgument(\sprintf('Missing parameter "HostedZoneId" for "%s". The value cannot be null.', __CLASS__));
        }
        $v = preg_replace('#^(/hostedzone/|/change/|/delegationset/)#', '', $v);
        $uri['Id'] = $v;
        $uriString = '/2013-04-01/hostedzone/' . rawurlencode($uri['Id']) . '/rrset';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setHostedZoneId(?string $value): self
    {
        $this->hostedZoneId = $value;

        return $this;
    }

    public function setMaxItems(?string $value): self
    {
        $this->maxItems = $value;

        return $this;
    }

    public function setStartRecordIdentifier(?string $value): self
    {
        $this->startRecordIdentifier = $value;

        return $this;
    }

    public function setStartRecordName(?string $value): self
    {
        $this->startRecordName = $value;

        return $this;
    }

    /**
     * @param RRType::*|null $value
     */
    public function setStartRecordType(?string $value): self
    {
        $this->startRecordType = $value;

        return $this;
    }
}
