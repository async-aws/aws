<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * A request to retrieve a list of the public and private hosted zones that are associated with the current account.
 */
final class ListHostedZonesRequest extends Input
{
    /**
     * If the value of `IsTruncated` in the previous response was `true`, you have more hosted zones. To get more hosted
     * zones, submit another `ListHostedZones` request.
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
     * @param array{
     *   Marker?: string,
     *   MaxItems?: string,
     *   DelegationSetId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        $this->delegationSetId = $input['DelegationSetId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDelegationSetId(): ?string
    {
        return $this->delegationSetId;
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
