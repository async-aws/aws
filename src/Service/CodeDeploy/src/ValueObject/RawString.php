<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the location of an AWS Lambda deployment revision stored as a RawString.
 */
final class RawString
{
    /**
     * The YAML-formatted or JSON-formatted revision string. It includes information about which Lambda function to update
     * and optional Lambda functions that validate deployment lifecycle events.
     */
    private $content;

    /**
     * The SHA256 hash value of the revision content.
     */
    private $sha256;

    /**
     * @param array{
     *   content?: null|string,
     *   sha256?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->content = $input['content'] ?? null;
        $this->sha256 = $input['sha256'] ?? null;
    }

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
