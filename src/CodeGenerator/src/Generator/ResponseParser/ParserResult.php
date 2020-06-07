<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

/**
 * @internal
 */
class ParserResult
{
    private $body;

    /**
     * Classes to import.
     *
     * @var array<string>
     */
    private $usedClasses;

    public function __construct(string $body, array $usedClasses)
    {
        $this->body = $body;
        $this->usedClasses = $usedClasses;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUsedClasses(): array
    {
        return $this->usedClasses;
    }
}
