<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

/**
 * Provides methods to store and load cached data in a multi-concurrent system.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class Cache
{
    /**
     * @var string
     */
    private $cacheFile;

    /**
     * @var bool
     */
    private $splitFile;

    /**
     * @var resource
     */
    private $handle;

    public function __construct(string $cacheFile)
    {
        $this->cacheFile = $cacheFile;
        $this->splitFile = is_dir($cacheFile);
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        $this->lock($key);

        try {
            rewind($this->handle);
            $data = json_decode(@stream_get_contents($this->handle), true);

            return $this->splitFile ? $data : ($data[$key] ?? null);
        } finally {
            $this->unlock();
        }
    }

    /**
     * @param callable(mixed): mixed $callable
     *
     * @return mixed
     */
    public function update(string $key, callable $callable)
    {
        $this->lock($key);

        try {
            rewind($this->handle);
            $data = json_decode(@stream_get_contents($this->handle), true);

            if ($this->splitFile) {
                $data = $newContent = $callable($data ?? null);
            } else {
                $data[$key] = $newContent = $callable($data[$key] ?? null);
            }

            ftruncate($this->handle, 0);
            rewind($this->handle);
            fwrite($this->handle, json_encode($data));

            return $newContent;
        } finally {
            $this->unlock();
        }
    }

    /**
     * @param mixed $content
     */
    public function set(string $key, $content): void
    {
        $this->update($key, static function () use ($content) {
            return $content;
        });
    }

    private function unlock(): void
    {
        flock($this->handle, \LOCK_UN);
        fclose($this->handle);
    }

    private function lock(string $key): void
    {
        set_error_handler(function ($type, $msg) use (&$error) {
            $error = $msg;

            return true;
        });
        $filename = $this->cacheFile;
        if ($this->splitFile) {
            $filename .= '/' . md5($key);
        }
        if (!$this->handle = fopen($filename, 'r+')) {
            if ($this->handle = fopen($filename, 'x')) {
                chmod($filename, 0666);
            } elseif (!$this->handle = fopen($filename, 'r+')) {
                usleep(100); // Give some time for chmod() to complete
                $this->handle = fopen($filename, 'r+');
            }
        }
        restore_error_handler();

        flock($this->handle, \LOCK_EX);
    }
}
