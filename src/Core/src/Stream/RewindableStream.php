<?php

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Provides a Stream that can be read several time.
 *
 * @internal
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class RewindableStream implements Stream
{
    private $content;

    /**
     * @var Stream
     */
    private $fallback;

    private function __construct(Stream $content)
    {
        $this->content = $content;
    }

    public static function create($content): RewindableStream
    {
        if ($content instanceof self) {
            return $content;
        }
        if ($content instanceof Stream) {
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
                // switch to filesystem
                if ($size > 1) {
                    $memoryStream = $resource;
                    $resource = \tmpfile();
                    $this->fallback = ResourceStream::create($resource);
                    $inmemory = false;

                    \fseek($memoryStream, 0);
                    \stream_copy_to_stream($memoryStream, $resource);
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
