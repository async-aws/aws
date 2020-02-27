<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Convert an iterator into a Stream.
 *
 * @internal
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class IterableStream implements Stream
{
    private $content;

    private function __construct(iterable $content)
    {
        $this->content = $content;
    }

    public static function create($content): IterableStream
    {
        if ($content instanceof self) {
            return $content;
        }
        if (\is_iterable($content)) {
            return new self($content);
        }

        throw new InvalidArgument(sprintf('Expect content to be an iterable. "%s" given.', \is_object($content) ? \get_class($content) : \gettype($content)));
    }

    public function length(): ?int
    {
        return null;
    }

    public function stringify(): string
    {
        if ($this->content instanceof \Traversable) {
            return \implode('', \iterator_to_array($this->content));
        }

        return \implode('', \iterator_to_array((function () {yield from $this->content; })()));
    }

    public function getIterator(): \Traversable
    {
        yield from $this->content;
    }
}
