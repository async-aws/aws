<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\SourceAuthType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the authorization settings for CodeBuild to access the source code to be built.
 */
final class SourceAuth
{
    /**
     * The authorization type to use. Valid options are OAUTH, CODECONNECTIONS, or SECRETS_MANAGER.
     *
     * @var SourceAuthType::*
     */
    private $type;

    /**
     * The resource value that applies to the specified authorization type.
     *
     * @var string|null
     */
    private $resource;

    /**
     * @param array{
     *   type: SourceAuthType::*,
     *   resource?: string|null,
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
     *   resource?: string|null,
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
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "SourceAuthType".', __CLASS__, $v));
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
