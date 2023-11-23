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
     * @var array<string, string>
     */
    private $extraMethodArgs;

    /**
     * @param list<string>          $usedClasses
     * @param array<string, string> $extraMethodArgs
     */
    public function __construct(string $body, array $usedClasses = [], array $extraMethodArgs = [])
    {
        $this->body = $body;
        $this->usedClasses = $usedClasses;
        $this->extraMethodArgs = $extraMethodArgs;
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

    /**
     * @return array<string, string>
     */
    public function getExtraMethodArgs(): array
    {
        return $this->extraMethodArgs;
    }
}
