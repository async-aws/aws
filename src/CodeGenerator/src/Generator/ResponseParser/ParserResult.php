<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use Nette\PhpGenerator\Method;

/**
 * @internal
 */
class ParserResult
{
    private $body;

    /**
     * Classes to import.
     *
     * @var ClassName[]
     */
    private $usedClasses;

    /**
     * @var Method[]
     */
    private $extraMethods;

    public function __construct(string $body, array $usedClasses = [], array $extraMethods = [])
    {
        $this->body = $body;
        $this->usedClasses = $usedClasses;
        $this->extraMethods = $extraMethods;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUsedClasses(): array
    {
        return $this->usedClasses;
    }

    public function getExtraMethods(): array
    {
        return $this->extraMethods;
    }
}
