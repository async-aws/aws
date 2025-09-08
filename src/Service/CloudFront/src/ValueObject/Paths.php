<?php

namespace AsyncAws\CloudFront\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A complex type that contains information about the objects that you want to invalidate. For more information, see
 * Specifying the Objects to Invalidate [^1] in the *Amazon CloudFront Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html#invalidation-specifying-objects
 */
final class Paths
{
    /**
     * The number of invalidation paths specified for the objects that you want to invalidate.
     *
     * @var int
     */
    private $quantity;

    /**
     * A complex type that contains a list of the paths that you want to invalidate.
     *
     * @var string[]|null
     */
    private $items;

    /**
     * @param array{
     *   Quantity: int,
     *   Items?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->quantity = $input['Quantity'] ?? $this->throwException(new InvalidArgument('Missing required field "Quantity".'));
        $this->items = $input['Items'] ?? null;
    }

    /**
     * @param array{
     *   Quantity: int,
     *   Items?: string[]|null,
     * }|Paths $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getItems(): array
    {
        return $this->items ?? [];
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->quantity;
        $node->appendChild($document->createElement('Quantity', (string) $v));
        if (null !== $v = $this->items) {
            $node->appendChild($nodeList = $document->createElement('Items'));
            foreach ($v as $item) {
                $nodeList->appendChild($document->createElement('Path', $item));
            }
        }
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
