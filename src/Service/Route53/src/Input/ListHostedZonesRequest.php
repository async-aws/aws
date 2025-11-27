<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Route53\Enum\HostedZoneType;

/**
 * A request to retrieve a list of the public and private hosted zones that are associated with the current Amazon Web
 * Services account.
 */
final class ListHostedZonesRequest extends Input
{
    /**
     * If the value of `IsTruncated` in the previous response was `true`, you have more hosted zones. To get more hosted
     * zones, submit another `ListHostedZones` request.
     *
     * For the value of `marker`, specify the value of `NextMarker` from the previous response, which is the ID of the first
     * hosted zone that Amazon Route 53 will return if you submit another request.
     *
     * If the value of `IsTruncated` in the previous response was `false`, there are no more hosted zones to get.
     *
     * @var string|null
     */
    private $marker;

    /**
     * (Optional) The maximum number of hosted zones that you want Amazon Route 53 to return. If you have more than
     * `maxitems` hosted zones, the value of `IsTruncated` in the response is `true`, and the value of `NextMarker` is the
     * hosted zone ID of the first hosted zone that Route 53 will return if you submit another request.
     *
     * @var string|null
     */
    private $maxItems;

    /**
     * If you're using reusable delegation sets and you want to list all of the hosted zones that are associated with a
     * reusable delegation set, specify the ID of that reusable delegation set.
     *
     * @var string|null
     */
    private $delegationSetId;

    /**
     * (Optional) Specifies if the hosted zone is private.
     *
     * @var HostedZoneType::*|null
     */
    private $hostedZoneType;

    /**
     * @param array{
     *   Marker?: string|null,
     *   MaxItems?: string|null,
     *   DelegationSetId?: string|null,
     *   HostedZoneType?: HostedZoneType::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        $this->delegationSetId = $input['DelegationSetId'] ?? null;
        $this->hostedZoneType = $input['HostedZoneType'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Marker?: string|null,
     *   MaxItems?: string|null,
     *   DelegationSetId?: string|null,
     *   HostedZoneType?: HostedZoneType::*|null,
     *   '@region'?: string|null,
     * }|ListHostedZonesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDelegationSetId(): ?string
    {
        return $this->delegationSetId;
    }

    /**
     * @return HostedZoneType::*|null
     */
    public function getHostedZoneType(): ?string
    {
        return $this->hostedZoneType;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxItems(): ?string
    {
        return $this->maxItems;
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
        if (null !== $this->marker) {
            $query['marker'] = $this->marker;
        }
        if (null !== $this->maxItems) {
            $query['maxitems'] = $this->maxItems;
        }
        if (null !== $this->delegationSetId) {
            $query['delegationsetid'] = $this->delegationSetId;
        }
        if (null !== $this->hostedZoneType) {
            if (!HostedZoneType::exists($this->hostedZoneType)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "HostedZoneType" for "%s". The value "%s" is not a valid "HostedZoneType".', __CLASS__, $this->hostedZoneType));
            }
            $query['hostedzonetype'] = $this->hostedZoneType;
        }

        // Prepare URI
        $uriString = '/2013-04-01/hostedzone';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDelegationSetId(?string $value): self
    {
        $this->delegationSetId = $value;

        return $this;
    }

    /**
     * @param HostedZoneType::*|null $value
     */
    public function setHostedZoneType(?string $value): self
    {
        $this->hostedZoneType = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMaxItems(?string $value): self
    {
        $this->maxItems = $value;

        return $this;
    }
}
