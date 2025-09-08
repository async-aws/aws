<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * (Resource record sets only): A complex type that lets you specify where your resources are located. Only one of
 * `LocalZoneGroup`, `Coordinates`, or `Amazon Web ServicesRegion` is allowed per request at a time.
 *
 * For more information about geoproximity routing, see Geoproximity routing [^1] in the *Amazon Route 53 Developer
 * Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/routing-policy-geoproximity.html
 */
final class GeoProximityLocation
{
    /**
     * The Amazon Web Services Region the resource you are directing DNS traffic to, is in.
     *
     * @var string|null
     */
    private $awsRegion;

    /**
     * Specifies an Amazon Web Services Local Zone Group.
     *
     * A local Zone Group is usually the Local Zone code without the ending character. For example, if the Local Zone is
     * `us-east-1-bue-1a` the Local Zone Group is `us-east-1-bue-1`.
     *
     * You can identify the Local Zones Group for a specific Local Zone by using the describe-availability-zones [^1] CLI
     * command:
     *
     * This command returns: `"GroupName": "us-west-2-den-1"`, specifying that the Local Zone `us-west-2-den-1a` belongs to
     * the Local Zone Group `us-west-2-den-1`.
     *
     * [^1]: https://docs.aws.amazon.com/cli/latest/reference/ec2/describe-availability-zones.html
     *
     * @var string|null
     */
    private $localZoneGroup;

    /**
     * Contains the longitude and latitude for a geographic region.
     *
     * @var Coordinates|null
     */
    private $coordinates;

    /**
     * The bias increases or decreases the size of the geographic region from which Route 53 routes traffic to a resource.
     *
     * To use `Bias` to change the size of the geographic region, specify the applicable value for the bias:
     *
     * - To expand the size of the geographic region from which Route 53 routes traffic to a resource, specify a positive
     *   integer from 1 to 99 for the bias. Route 53 shrinks the size of adjacent regions.
     * - To shrink the size of the geographic region from which Route 53 routes traffic to a resource, specify a negative
     *   bias of -1 to -99. Route 53 expands the size of adjacent regions.
     *
     * @var int|null
     */
    private $bias;

    /**
     * @param array{
     *   AWSRegion?: string|null,
     *   LocalZoneGroup?: string|null,
     *   Coordinates?: Coordinates|array|null,
     *   Bias?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->awsRegion = $input['AWSRegion'] ?? null;
        $this->localZoneGroup = $input['LocalZoneGroup'] ?? null;
        $this->coordinates = isset($input['Coordinates']) ? Coordinates::create($input['Coordinates']) : null;
        $this->bias = $input['Bias'] ?? null;
    }

    /**
     * @param array{
     *   AWSRegion?: string|null,
     *   LocalZoneGroup?: string|null,
     *   Coordinates?: Coordinates|array|null,
     *   Bias?: int|null,
     * }|GeoProximityLocation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAwsRegion(): ?string
    {
        return $this->awsRegion;
    }

    public function getBias(): ?int
    {
        return $this->bias;
    }

    public function getCoordinates(): ?Coordinates
    {
        return $this->coordinates;
    }

    public function getLocalZoneGroup(): ?string
    {
        return $this->localZoneGroup;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->awsRegion) {
            $node->appendChild($document->createElement('AWSRegion', $v));
        }
        if (null !== $v = $this->localZoneGroup) {
            $node->appendChild($document->createElement('LocalZoneGroup', $v));
        }
        if (null !== $v = $this->coordinates) {
            $node->appendChild($child = $document->createElement('Coordinates'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->bias) {
            $node->appendChild($document->createElement('Bias', (string) $v));
        }
    }
}
