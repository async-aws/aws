<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\File;

/**
 * DumpFile only when it has not been modified.
 * This class takes into account that a post-process can alter the content of the file (php-cs-fier).
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class CachedFileDumper extends FileDumper
{
    private $cache;

    private $status = [];

    private $added = [];

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->cache->update(__CLASS__, function ($status) {
            $this->status = [];
            foreach ($status ?? [] as $filename => $flags) {
                if (is_file($filename)) {
                    $this->status[$filename] = $flags;
                }
            }

            return $this->status;
        });
    }

    public function __destruct()
    {
        $this->cache->update(__CLASS__, function ($status) {
            $status = $status ?? [];
            foreach ($this->added as $filename => $md5) {
                if (is_file($filename)) {
                    $status[$filename][0] = $md5;
                    $status[$filename][1] = md5_file($filename);
                    if ($status[$filename][1] === $status[$filename][0]) {
                        unset($status[$filename]);
                    }
                } else {
                    unset($status[$filename]);
                }
            }

            return $status;
        });
    }

    public function dump(string $filename, string $content): void
    {
        if ($this->isFresh($filename, $content)) {
            return;
        }

        parent::dump($filename, $content);

        $this->added[$filename] = md5($content);
    }

    private function isFresh(string $filename, string $originalContent): bool
    {
        if (!is_file($filename)) {
            return false;
        }
        if (!isset($this->status[$filename])) {
            return false;
        }
        if (!isset($this->status[$filename][1]) || md5_file($filename) !== $this->status[$filename][1]) {
            return false;
        }
        if (!isset($this->status[$filename][0]) || md5($originalContent) !== $this->status[$filename][0]) {
            return false;
        }

        return true;
    }
}
