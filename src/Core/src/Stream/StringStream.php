<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Convert a string into a Stream.
 *
 * @internal
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class StringStream implements Stream
{
    private $content;

    private $lengthCache;

    private function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function create($content): StringStream
    {
        if ($content instanceof self) {
            return $content;
        }
        if ($content instanceof Stream) {
            return new self($content->stringify());
        }
        if (\is_string($content)) {
            return new self($content);
        }

        throw new InvalidArgument(sprintf('Expect content to be a "%s" or as "string". "%s" given.', Stream::class, \is_object($content) ? \get_class($content) : \gettype($content)));
    }

    public function length(): int
    {
        return $this->lengthCache ?? $this->lengthCache = \strlen($this->content);
    }

    public function stringify(): string
    {
        return $this->content;
    }

    public function getIterator(): \Traversable
    {
        yield $this->content;
    }

    public function hash(string $algo = 'sha256', bool $raw = false): string
    {
        return \hash($algo, $this->content, $raw);
    }
}
