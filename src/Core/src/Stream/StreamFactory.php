<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Create Streams.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class StreamFactory
{
    public static function create($content): Stream
    {
        if (null === $content || \is_string($content)) {
            return StringStream::create($content ?? '');
        }
        if (\is_callable($content)) {
            return RewindableStream::create(CallableStream::create($content));
        }
        if (\is_iterable($content)) {
            return IterableStream::create($content);
        }
        if (\is_resource($content)) {
            return ResourceStream::create($content);
        }

        throw new InvalidArgument(sprintf('Unexpected content type "%s".', \is_object($content) ? \get_class($content) : \gettype($content)));
    }
}
