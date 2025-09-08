<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The information for a change request.
 */
final class ChangeBatch
{
    /**
     * *Optional:* Any comments you want to include about a change batch request.
     *
     * @var string|null
     */
    private $comment;

    /**
     * Information about the changes to make to the record sets.
     *
     * @var Change[]
     */
    private $changes;

    /**
     * @param array{
     *   Comment?: string|null,
     *   Changes: array<Change|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->comment = $input['Comment'] ?? null;
        $this->changes = isset($input['Changes']) ? array_map([Change::class, 'create'], $input['Changes']) : $this->throwException(new InvalidArgument('Missing required field "Changes".'));
    }

    /**
     * @param array{
     *   Comment?: string|null,
     *   Changes: array<Change|array>,
     * }|ChangeBatch $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Change[]
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->comment) {
            $node->appendChild($document->createElement('Comment', $v));
        }
        $v = $this->changes;

        $node->appendChild($nodeList = $document->createElement('Changes'));
        foreach ($v as $item) {
            $nodeList->appendChild($child = $document->createElement('Change'));

            $item->requestBody($child, $document);
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
