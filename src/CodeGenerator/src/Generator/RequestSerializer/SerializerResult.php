<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

/**
 * @internal
 */
class SerializerResult
{
    /**
     * @var string
     */
    private $body;

    /**
     * Classes to import.
     *
     * @var list<string>
     */
    private $usedClasses;

    /**
     * @param list<string> $usedClasses
     */
    public function __construct(string $body, array $usedClasses = [])
    {
        $this->body = $body;
        $this->usedClasses = $usedClasses;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return list<string>
     */
    public function getUsedClasses(): array
    {
        return $this->usedClasses;
    }
}
