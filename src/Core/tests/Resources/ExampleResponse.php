<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Resources;

use AsyncAws\Core\Result;

class ExampleResponse extends Result
{
    private $int;

    private $float;

    private $bool;

    private $array;

    private $string;

    public function getInt(): int
    {
        return $this->int;
    }

    public function getFloat(): float
    {
        return $this->float;
    }

    public function getBool(): bool
    {
        return $this->bool;
    }

    public function getArray(): array
    {
        return $this->array;
    }

    public function getString(): string
    {
        return $this->string;
    }
}
