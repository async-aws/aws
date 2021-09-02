<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Route53\Enum\VPCRegion;

/**
 * (Private hosted zones only) A complex type that contains information about the Amazon VPC that you're associating
 * with this hosted zone.
 * You can specify only one Amazon VPC when you create a private hosted zone. To associate additional Amazon VPCs with
 * the hosted zone, use AssociateVPCWithHostedZone after you create a hosted zone.
 *
 * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_AssociateVPCWithHostedZone.html
 */
final class VPC
{
    /**
     * (Private hosted zones only) The region that an Amazon VPC was created in.
     */
    private $vpcRegion;

    private $vpcId;

    /**
     * @param array{
     *   VPCRegion?: null|VPCRegion::*,
     *   VPCId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vpcRegion = $input['VPCRegion'] ?? null;
        $this->vpcId = $input['VPCId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getVpcId(): ?string
    {
        return $this->vpcId;
    }

    /**
     * @return VPCRegion::*|null
     */
    public function getVpcRegion(): ?string
    {
        return $this->vpcRegion;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->vpcRegion) {
            if (!VPCRegion::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "VPCRegion" for "%s". The value "%s" is not a valid "VPCRegion".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('VPCRegion', $v));
        }
        if (null !== $v = $this->vpcId) {
            $node->appendChild($document->createElement('VPCId', $v));
        }
    }
}
