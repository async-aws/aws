<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Container for the objects to delete.
 */
final class Delete
{
    /**
     * The object to delete.
     *
     * > **Directory buckets** - For directory buckets, an object that's composed entirely of whitespace characters is not
     * > supported by the `DeleteObjects` API operation. The request will receive a `400 Bad Request` error and none of the
     * > objects in the request will be deleted.
     *
     * @var ObjectIdentifier[]
     */
    private $objects;

    /**
     * Element to enable quiet mode for the request. When you add this element, you must set its value to `true`.
     *
     * @var bool|null
     */
    private $quiet;

    /**
     * @param array{
     *   Objects: array<ObjectIdentifier|array>,
     *   Quiet?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->objects = isset($input['Objects']) ? array_map([ObjectIdentifier::class, 'create'], $input['Objects']) : $this->throwException(new InvalidArgument('Missing required field "Objects".'));
        $this->quiet = $input['Quiet'] ?? null;
    }

    /**
     * @param array{
     *   Objects: array<ObjectIdentifier|array>,
     *   Quiet?: null|bool,
     * }|Delete $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ObjectIdentifier[]
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    public function getQuiet(): ?bool
    {
        return $this->quiet;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->objects;
        foreach ($v as $item) {
            $node->appendChild($child = $document->createElement('Object'));

            $item->requestBody($child, $document);
        }

        if (null !== $v = $this->quiet) {
            $node->appendChild($document->createElement('Quiet', $v ? 'true' : 'false'));
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
