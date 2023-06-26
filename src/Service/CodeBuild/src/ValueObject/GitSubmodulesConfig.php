<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the Git submodules configuration for an CodeBuild build project.
 */
final class GitSubmodulesConfig
{
    /**
     * Set to true to fetch Git submodules for your CodeBuild build project.
     *
     * @var bool
     */
    private $fetchSubmodules;

    /**
     * @param array{
     *   fetchSubmodules: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fetchSubmodules = $input['fetchSubmodules'] ?? $this->throwException(new InvalidArgument('Missing required field "fetchSubmodules".'));
    }

    /**
     * @param array{
     *   fetchSubmodules: bool,
     * }|GitSubmodulesConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFetchSubmodules(): bool
    {
        return $this->fetchSubmodules;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->fetchSubmodules;
        $payload['fetchSubmodules'] = (bool) $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
