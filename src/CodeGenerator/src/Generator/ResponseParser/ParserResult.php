<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

/**
 * @internal
 */
class ParserResult
{
    /**
     * @var string
     */
    private $body;

    /**
     * Classes to import.
     *
     * @var array<string>
     */
    private $imports;

    public function __construct(string $body, array $imports)
    {
        $this->body = $body;
        $this->imports = $imports;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getImports(): array
    {
        return $this->imports;
    }
}
