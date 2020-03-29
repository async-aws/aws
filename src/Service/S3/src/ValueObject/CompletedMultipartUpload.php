<?php

namespace AsyncAws\S3\ValueObject;

class CompletedMultipartUpload
{
    /**
     * Array of CompletedPart data types.
     */
    private $Parts;

    /**
     * @param array{
     *   Parts?: null|\AsyncAws\S3\ValueObject\CompletedPart[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Parts = array_map([CompletedPart::class, 'create'], $input['Parts'] ?? []);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CompletedPart[]
     */
    public function getParts(): array
    {
        return $this->Parts;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        foreach ($this->Parts as $item) {
            $node->appendChild($child = $document->createElement('Part'));

            $item->requestBody($child, $document);
        }
    }
}
