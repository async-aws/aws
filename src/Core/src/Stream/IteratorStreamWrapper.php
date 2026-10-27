<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\RuntimeException;

/**
 * Read-only PHP stream that pulls chunks from an iterator only when a read needs them, so the
 * iterator's exceptions are thrown by that read.
 *
 * @internal
 */
final class IteratorStreamWrapper
{
    private const PROTOCOL = 'async-aws-iterator';

    /**
     * @var resource|null
     */
    public $context;

    /**
     * @var \Iterator<string>
     */
    private $chunks;

    /**
     * @var string
     */
    private $buffer = '';

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var bool
     */
    private $started = false;

    public function __construct()
    {
        $this->chunks = new \EmptyIterator();
    }

    /**
     * @param \Iterator<string> $chunks
     *
     * @return resource
     */
    public static function createResource(\Iterator $chunks)
    {
        if (!\in_array(self::PROTOCOL, stream_get_wrappers(), true) && !stream_wrapper_register(self::PROTOCOL, self::class)) {
            throw new RuntimeException('Unable to register the stream wrapper.');
        }

        $resource = fopen(self::PROTOCOL . '://', 'r', false, stream_context_create([self::PROTOCOL => ['chunks' => $chunks]]));
        if (false === $resource) {
            throw new RuntimeException('Unable to open the stream.');
        }

        // PHP reports a user-space stream as seekable until a seek fails, so fail one up front
        @fseek($resource, 0, \SEEK_CUR);

        return $resource;
    }

    public function stream_open(string $path, string $mode, int $options, ?string &$openedPath): bool
    {
        $chunks = null === $this->context ? null : (stream_context_get_options($this->context)[self::PROTOCOL]['chunks'] ?? null);
        if (!$chunks instanceof \Iterator) {
            return false;
        }

        $this->chunks = $chunks;

        return true;
    }

    public function stream_read(int $count): string
    {
        while ($this->offset >= \strlen($this->buffer)) {
            $this->buffer = '';
            $this->offset = 0;
            if ($this->started) {
                $this->chunks->next();
            }
            $this->started = true;

            if (!$this->chunks->valid()) {
                return '';
            }

            $this->buffer = (string) $this->chunks->current();
        }

        $data = substr($this->buffer, $this->offset, $count);
        $this->offset += \strlen($data);

        return $data;
    }

    public function stream_eof(): bool
    {
        return $this->started && !$this->chunks->valid();
    }

    public function stream_set_option(int $option, int $arg1, ?int $arg2): bool
    {
        return false;
    }

    /**
     * @return false
     */
    public function stream_stat(): bool
    {
        return false;
    }
}
