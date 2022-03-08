<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A source identifier and its corresponding version.
 */
final class ProjectSourceVersion
{
    /**
     * An identifier for a source in the build project. The identifier can only contain alphanumeric characters and
     * underscores, and must be less than 128 characters in length.
     */
    private $sourceIdentifier;

    /**
     * The source version for the corresponding source identifier. If specified, must be one of:.
     */
    private $sourceVersion;

    /**
     * @param array{
     *   sourceIdentifier: string,
     *   sourceVersion: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sourceIdentifier = $input['sourceIdentifier'] ?? null;
        $this->sourceVersion = $input['sourceVersion'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSourceIdentifier(): string
    {
        return $this->sourceIdentifier;
    }

    public function getSourceVersion(): string
    {
        return $this->sourceVersion;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->sourceIdentifier) {
            throw new InvalidArgument(sprintf('Missing parameter "sourceIdentifier" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['sourceIdentifier'] = $v;
        if (null === $v = $this->sourceVersion) {
            throw new InvalidArgument(sprintf('Missing parameter "sourceVersion" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['sourceVersion'] = $v;

        return $payload;
    }
}
