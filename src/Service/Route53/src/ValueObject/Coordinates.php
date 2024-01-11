<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A complex type that lists the coordinates for a geoproximity resource record.
 */
final class Coordinates
{
    /**
     * Specifies a coordinate of the north–south position of a geographic point on the surface of the Earth (-90 - 90).
     *
     * @var string
     */
    private $latitude;

    /**
     * Specifies a coordinate of the east–west position of a geographic point on the surface of the Earth (-180 - 180).
     *
     * @var string
     */
    private $longitude;

    /**
     * @param array{
     *   Latitude: string,
     *   Longitude: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->latitude = $input['Latitude'] ?? $this->throwException(new InvalidArgument('Missing required field "Latitude".'));
        $this->longitude = $input['Longitude'] ?? $this->throwException(new InvalidArgument('Missing required field "Longitude".'));
    }

    /**
     * @param array{
     *   Latitude: string,
     *   Longitude: string,
     * }|Coordinates $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->latitude;
        $node->appendChild($document->createElement('Latitude', $v));
        $v = $this->longitude;
        $node->appendChild($document->createElement('Longitude', $v));
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
