<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * *Alias resource record sets only:* Information about the Amazon Web Services resource, such as a CloudFront
 * distribution or an Amazon S3 bucket, that you want to route traffic to.
 * If you're creating resource records sets for a private hosted zone, note the following:.
 *
 * - You can't create an alias resource record set in a private hosted zone to route traffic to a CloudFront
 *   distribution.
 * - For information about creating failover resource record sets in a private hosted zone, see Configuring Failover in
 *   a Private Hosted Zone in the *Amazon Route 53 Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/dns-failover-private-hosted-zones.html
 */
final class AliasTarget
{
    /**
     * *Alias resource records sets only*: The value used depends on where you want to route traffic:.
     */
    private $hostedZoneId;

    /**
     * *Alias resource record sets only:* The value that you specify depends on where you want to route queries:.
     */
    private $dnsName;

    /**
     * *Applies only to alias, failover alias, geolocation alias, latency alias, and weighted alias resource record sets:*
     * When `EvaluateTargetHealth` is `true`, an alias resource record set inherits the health of the referenced Amazon Web
     * Services resource, such as an ELB load balancer or another resource record set in the hosted zone.
     */
    private $evaluateTargetHealth;

    /**
     * @param array{
     *   HostedZoneId: string,
     *   DNSName: string,
     *   EvaluateTargetHealth: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->hostedZoneId = $input['HostedZoneId'] ?? null;
        $this->dnsName = $input['DNSName'] ?? null;
        $this->evaluateTargetHealth = $input['EvaluateTargetHealth'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDnsName(): string
    {
        return $this->dnsName;
    }

    public function getEvaluateTargetHealth(): bool
    {
        return $this->evaluateTargetHealth;
    }

    public function getHostedZoneId(): string
    {
        return $this->hostedZoneId;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null === $v = $this->hostedZoneId) {
            throw new InvalidArgument(sprintf('Missing parameter "HostedZoneId" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('HostedZoneId', $v));
        if (null === $v = $this->dnsName) {
            throw new InvalidArgument(sprintf('Missing parameter "DNSName" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('DNSName', $v));
        if (null === $v = $this->evaluateTargetHealth) {
            throw new InvalidArgument(sprintf('Missing parameter "EvaluateTargetHealth" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('EvaluateTargetHealth', $v ? 'true' : 'false'));
    }
}
