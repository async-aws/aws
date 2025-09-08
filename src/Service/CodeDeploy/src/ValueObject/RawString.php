<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * A revision for an Lambda deployment that is a YAML-formatted or JSON-formatted string. For Lambda deployments, the
 * revision is the same as the AppSpec file.
 */
final class RawString
{
    /**
     * The YAML-formatted or JSON-formatted revision string. It includes information about which Lambda function to update
     * and optional Lambda functions that validate deployment lifecycle events.
     *
     * @var string|null
     */
    private $content;

    /**
     * The SHA256 hash value of the revision content.
     *
     * @var string|null
     */
    private $sha256;

    /**
     * @param array{
     *   content?: string|null,
     *   sha256?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->content = $input['content'] ?? null;
        $this->sha256 = $input['sha256'] ?? null;
    }

    /**
     * @param array{
     *   content?: string|null,
     *   sha256?: string|null,
     * }|RawString $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getSha256(): ?string
    {
        return $this->sha256;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->content) {
            $payload['content'] = $v;
        }
        if (null !== $v = $this->sha256) {
            $payload['sha256'] = $v;
        }

        return $payload;
    }
}
