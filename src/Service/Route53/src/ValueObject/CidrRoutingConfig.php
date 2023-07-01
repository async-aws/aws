<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The object that is specified in resource record set object when you are linking a resource record set to a CIDR
 * location.
 *
 * A `LocationName` with an asterisk “*” can be used to create a default CIDR record. `CollectionId` is still
 * required for default record.
 */
final class CidrRoutingConfig
{
    /**
     * The CIDR collection ID.
     */
    private $collectionId;

    /**
     * The CIDR collection location name.
     */
    private $locationName;

    /**
     * @param array{
     *   CollectionId: string,
     *   LocationName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->collectionId = $input['CollectionId'] ?? $this->throwException(new InvalidArgument('Missing required field "CollectionId".'));
        $this->locationName = $input['LocationName'] ?? $this->throwException(new InvalidArgument('Missing required field "LocationName".'));
    }

    /**
     * @param array{
     *   CollectionId: string,
     *   LocationName: string,
     * }|CidrRoutingConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionId(): string
    {
        return $this->collectionId;
    }

    public function getLocationName(): string
    {
        return $this->locationName;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->collectionId;
        $node->appendChild($document->createElement('CollectionId', $v));
        $v = $this->locationName;
        $node->appendChild($document->createElement('LocationName', $v));
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
