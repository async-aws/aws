<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Convert an iterator into a Stream.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
final class GeneratorIterableStream extends IterableStream implements ReadOnceResultStream
{
    public static function create($content): IterableStream
    {
        if ($content instanceof self) {
            return $content;
        }
        if ($content instanceof \Generator) {
            return new self($content);
        }

        throw new InvalidArgument(sprintf('Expect content to be a Generator. "%s" given.', \is_object($content) ? \get_class($content) : \gettype($content)));
    }
}
