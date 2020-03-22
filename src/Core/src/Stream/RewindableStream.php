<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Provides a Stream that can be read several time.
 *
 * This is for internal use only. One cannot iterate only over a few items of the stream.
 * If iterating over a stream, the full stream must be consumed before calling methods:
 * - stringify
 * - length
 * - hash
 *
 * @internal
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class RewindableStream implements RequestStream
{
    private $content;

    /**
     * @var RequestStream
     */
    private $fallback;

    private function __construct(RequestStream $content)
    {
        $this->content = $content;
    }

    public static function create($content): RewindableStream
    {
        if ($content instanceof self) {
            return $content;
        }
        if ($content instanceof RequestStream) {
            return new self($content);
        }

        throw new InvalidArgument(sprintf('Expect content to be a "Stream". "%s" given.', \is_object($content) ? \get_class($content) : \gettype($content)));
    }

    public function length(): ?int
    {
        if (null !== $this->fallback) {
            return $this->fallback->length();
        }

        return $this->content->length();
    }

    public function stringify(): string
    {
        if (null !== $this->fallback) {
            return $this->fallback->stringify();
        }

        return \implode('', \iterator_to_array($this));
    }

    public function getIterator(): \Traversable
    {
        if (null !== $this->fallback) {
            yield from $this->fallback;

            return;
        }

        $resource = \fopen('php://memory', 'r+b');
        $this->fallback = ResourceStream::create($resource);

        $size = 0;
        $inmemory = true;
        foreach ($this->content as $chunk) {
            \fwrite($resource, $chunk);
            if ($inmemory) {
                $size += \strlen($chunk);
                // switch to filesystem if string larger that 1 Mb
                if ($size > 1024 * 1024) {
                    $memoryStream = $resource;
                    $resource = \tmpfile();
                    $this->fallback = ResourceStream::create($resource);
                    $inmemory = false;

                    \fseek($memoryStream, 0);
                    \stream_copy_to_stream($memoryStream, $resource);
                    \fclose($memoryStream);
                }
            }
            yield $chunk;
        }
    }

    public function hash(string $algo = 'sha256', bool $raw = false): string
    {
        if (null !== $this->fallback) {
            return $this->fallback->hash($algo, $raw);
        }

        $ctx = hash_init($algo);
        foreach ($this as $chunk) {
            hash_update($ctx, $chunk);
        }

        return hash_final($ctx, $raw);
    }
}
