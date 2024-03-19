<?php

namespace AsyncAws\Route53\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Route53\Enum\VPCRegion;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\VPC;

/**
 * A complex type that contains information about the request to create a public or private hosted zone.
 */
final class CreateHostedZoneRequest extends Input
{
    /**
     * The name of the domain. Specify a fully qualified domain name, for example, *www.example.com*. The trailing dot is
     * optional; Amazon Route 53 assumes that the domain name is fully qualified. This means that Route 53 treats
     * *www.example.com* (without a trailing dot) and *www.example.com.* (with a trailing dot) as identical.
     *
     * If you're creating a public hosted zone, this is the name you have registered with your DNS registrar. If your domain
     * name is registered with a registrar other than Route 53, change the name servers for your domain to the set of
     * `NameServers` that `CreateHostedZone` returns in `DelegationSet`.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * (Private hosted zones only) A complex type that contains information about the Amazon VPC that you're associating
     * with this hosted zone.
     *
     * You can specify only one Amazon VPC when you create a private hosted zone. If you are associating a VPC with a hosted
     * zone with this request, the paramaters `VPCId` and `VPCRegion` are also required.
     *
     * To associate additional Amazon VPCs with the hosted zone, use AssociateVPCWithHostedZone [^1] after you create a
     * hosted zone.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_AssociateVPCWithHostedZone.html
     *
     * @var VPC|null
     */
    private $vpc;

    /**
     * A unique string that identifies the request and that allows failed `CreateHostedZone` requests to be retried without
     * the risk of executing the operation twice. You must use a unique `CallerReference` string every time you submit a
     * `CreateHostedZone` request. `CallerReference` can be any unique string, for example, a date/time stamp.
     *
     * @required
     *
     * @var string|null
     */
    private $callerReference;

    /**
     * (Optional) A complex type that contains the following optional values:
     *
     * - For public and private hosted zones, an optional comment
     * - For private hosted zones, an optional `PrivateZone` element
     *
     * If you don't specify a comment or the `PrivateZone` element, omit `HostedZoneConfig` and the other elements.
     *
     * @var HostedZoneConfig|null
     */
    private $hostedZoneConfig;

    /**
     * If you want to associate a reusable delegation set with this hosted zone, the ID that Amazon Route 53 assigned to
     * the reusable delegation set when you created it. For more information about reusable delegation sets, see
     * CreateReusableDelegationSet [^1].
     *
     * If you are using a reusable delegation set to create a public hosted zone for a subdomain, make sure that the parent
     * hosted zone doesn't use one or more of the same name servers. If you have overlapping nameservers, the operation will
     * cause a `ConflictingDomainsExist` error.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateReusableDelegationSet.html
     *
     * @var string|null
     */
    private $delegationSetId;

    /**
     * @param array{
     *   Name?: string,
     *   VPC?: null|VPC|array,
     *   CallerReference?: string,
     *   HostedZoneConfig?: null|HostedZoneConfig|array,
     *   DelegationSetId?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->name = $input['Name'] ?? null;
        $this->vpc = isset($input['VPC']) ? VPC::create($input['VPC']) : null;
        $this->callerReference = $input['CallerReference'] ?? null;
        $this->hostedZoneConfig = isset($input['HostedZoneConfig']) ? HostedZoneConfig::create($input['HostedZoneConfig']) : null;
        $this->delegationSetId = $input['DelegationSetId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Name?: string,
     *   VPC?: null|VPC|array,
     *   CallerReference?: string,
     *   HostedZoneConfig?: null|HostedZoneConfig|array,
     *   DelegationSetId?: null|string,
     *   '@region'?: string|null,
     * }|CreateHostedZoneRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCallerReference(): ?string
    {
        return $this->callerReference;
    }

    public function getDelegationSetId(): ?string
    {
        return $this->delegationSetId;
    }

    public function getHostedZoneConfig(): ?HostedZoneConfig
    {
        return $this->hostedZoneConfig;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getVpc(): ?VPC
    {
        return $this->vpc;
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

        // Prepare URI
        $uriString = '/2013-04-01/hostedzone';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $document->appendChild($child = $document->createElement('CreateHostedZoneRequest'));
        $child->setAttribute('xmlns', 'https://route53.amazonaws.com/doc/2013-04-01/');
        $this->requestBody($child, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCallerReference(?string $value): self
    {
        $this->callerReference = $value;

        return $this;
    }

    public function setDelegationSetId(?string $value): self
    {
        $this->delegationSetId = $value;

        return $this;
    }

    public function setHostedZoneConfig(?HostedZoneConfig $value): self
    {
        $this->hostedZoneConfig = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setVpc(?VPC $value): self
    {
        $this->vpc = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('Name', $v));
        if (null !== $v = $this->vpc) {
            $node->appendChild($child = $document->createElement('VPC'));

            $v->requestBody($child, $document);
        }
        if (null === $v = $this->callerReference) {
            throw new InvalidArgument(sprintf('Missing parameter "CallerReference" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('CallerReference', $v));
        if (null !== $v = $this->hostedZoneConfig) {
            $node->appendChild($child = $document->createElement('HostedZoneConfig'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->delegationSetId) {
            $node->appendChild($document->createElement('DelegationSetId', $v));
        }
    }
}
