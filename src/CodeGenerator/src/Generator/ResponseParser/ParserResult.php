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
    /**
     * @var string
     */
    private $body;

    /**
     * Classes to import.
     *
     * @var list<ClassName>
     */
    private $usedClasses;

    /**
     * @var array<string, Method>
     */
    private $extraMethods;

    /**
     * @param list<ClassName>       $usedClasses
     * @param array<string, Method> $extraMethods
     */
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

    /**
     * @return list<ClassName>
     */
    public function getUsedClasses(): array
    {
        return $this->usedClasses;
    }

    /**
     * @return array<string, Method>
     */
    public function getExtraMethods(): array
    {
        return $this->extraMethods;
    }
}
