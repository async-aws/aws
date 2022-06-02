<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

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
        $this->collectionId = $input['CollectionId'] ?? null;
        $this->locationName = $input['LocationName'] ?? null;
    }

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
        if (null === $v = $this->collectionId) {
            throw new InvalidArgument(sprintf('Missing parameter "CollectionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('CollectionId', $v));
        if (null === $v = $this->locationName) {
            throw new InvalidArgument(sprintf('Missing parameter "LocationName" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('LocationName', $v));
    }
}
