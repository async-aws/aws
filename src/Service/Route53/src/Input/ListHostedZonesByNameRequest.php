<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Retrieves a list of the public and private hosted zones that are associated with the current account in ASCII order
 * by domain name.
 */
final class ListHostedZonesByNameRequest extends Input
{
    /**
     * (Optional) For your first request to `ListHostedZonesByName`, include the `dnsname` parameter only if you want to
     * specify the name of the first hosted zone in the response. If you don't include the `dnsname` parameter, Amazon Route
     * 53 returns all of the hosted zones that were created by the current account, in ASCII order. For subsequent requests,
     * include both `dnsname` and `hostedzoneid` parameters. For `dnsname`, specify the value of `NextDNSName` from the
     * previous response.
     *
     * @var string|null
     */
    private $dnsName;

    /**
     * (Optional) For your first request to `ListHostedZonesByName`, do not include the `hostedzoneid` parameter.
     *
     * @var string|null
     */
    private $hostedZoneId;

    /**
     * The maximum number of hosted zones to be included in the response body for this request. If you have more than
     * `maxitems` hosted zones, then the value of the `IsTruncated` element in the response is true, and the values of
     * `NextDNSName` and `NextHostedZoneId` specify the first hosted zone in the next group of `maxitems` hosted zones.
     *
     * @var string|null
     */
    private $maxItems;

    /**
     * @param array{
     *   DNSName?: string,
     *   HostedZoneId?: string,
     *   MaxItems?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->dnsName = $input['DNSName'] ?? null;
        $this->hostedZoneId = $input['HostedZoneId'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDnsName(): ?string
    {
        return $this->dnsName;
    }

    public function getHostedZoneId(): ?string
    {
        return $this->hostedZoneId;
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
        if (null !== $this->dnsName) {
            $query['dnsname'] = $this->dnsName;
        }
        if (null !== $this->hostedZoneId) {
            $query['hostedzoneid'] = $this->hostedZoneId;
        }
        if (null !== $this->maxItems) {
            $query['maxitems'] = $this->maxItems;
        }

        // Prepare URI
        $uriString = '/2013-04-01/hostedzonesbyname';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDnsName(?string $value): self
    {
        $this->dnsName = $value;

        return $this;
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
}
