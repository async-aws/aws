<?php

namespace AsyncAws\CloudFront\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class Paths
{
    /**
     * The number of invalidation paths specified for the objects that you want to invalidate.
     */
    private $Quantity;

    /**
     * A complex type that contains a list of the paths that you want to invalidate.
     */
    private $Items;

    /**
     * @param array{
     *   Quantity: int,
     *   Items?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Quantity = $input['Quantity'] ?? null;
        $this->Items = $input['Items'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getItems(): array
    {
        return $this->Items;
    }

    public function getQuantity(): int
    {
        return $this->Quantity;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null === $v = $this->Quantity) {
            throw new InvalidArgument(sprintf('Missing parameter "Quantity" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('Quantity', $v));

        $node->appendChild($nodeList = $document->createElement('Items'));
        foreach ($this->Items as $item) {
            $nodeList->appendChild($document->createElement('Path', $item));
        }
    }
}
