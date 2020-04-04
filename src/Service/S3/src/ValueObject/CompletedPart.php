<?php

namespace AsyncAws\S3\ValueObject;

final class CompletedPart
{
    /**
     * Entity tag returned when the part was uploaded.
     */
    private $ETag;

    /**
     * Part number that identifies the part. This is a positive integer between 1 and 10,000.
     */
    private $PartNumber;

    /**
     * @param array{
     *   ETag?: null|string,
     *   PartNumber?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ETag = $input['ETag'] ?? null;
        $this->PartNumber = $input['PartNumber'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getETag(): ?string
    {
        return $this->ETag;
    }

    public function getPartNumber(): ?int
    {
        return $this->PartNumber;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->ETag) {
            $node->appendChild($document->createElement('ETag', $v));
        }
        if (null !== $v = $this->PartNumber) {
            $node->appendChild($document->createElement('PartNumber', $v));
        }
    }
}
