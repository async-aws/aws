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
     */
    private $fetchSubmodules;

    /**
     * @param array{
     *   fetchSubmodules: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fetchSubmodules = $input['fetchSubmodules'] ?? null;
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
        if (null === $v = $this->fetchSubmodules) {
            throw new InvalidArgument(sprintf('Missing parameter "fetchSubmodules" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['fetchSubmodules'] = (bool) $v;

        return $payload;
    }
}
