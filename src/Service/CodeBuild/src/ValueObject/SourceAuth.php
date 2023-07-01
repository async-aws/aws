<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\SourceAuthType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the authorization settings for CodeBuild to access the source code to be built.
 *
 * This information is for the CodeBuild console's use only. Your code should not get or set this information directly.
 */
final class SourceAuth
{
    /**
     * > This data type is deprecated and is no longer accurate or used.
     *
     * The authorization type to use. The only valid value is `OAUTH`, which represents the OAuth authorization type.
     */
    private $type;

    /**
     * The resource value that applies to the specified authorization type.
     */
    private $resource;

    /**
     * @param array{
     *   type: SourceAuthType::*,
     *   resource?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->resource = $input['resource'] ?? null;
    }

    /**
     * @param array{
     *   type: SourceAuthType::*,
     *   resource?: null|string,
     * }|SourceAuth $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * @return SourceAuthType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->type;
        if (!SourceAuthType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "SourceAuthType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->resource) {
            $payload['resource'] = $v;
        }

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
