<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies the default server-side-encryption configuration.
 */
final class ServerSideEncryptionConfiguration
{
    /**
     * Container for information about a particular server-side encryption configuration rule.
     *
     * @var ServerSideEncryptionRule[]
     */
    private $rules;

    /**
     * @param array{
     *   Rules: array<ServerSideEncryptionRule|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rules = isset($input['Rules']) ? array_map([ServerSideEncryptionRule::class, 'create'], $input['Rules']) : $this->throwException(new InvalidArgument('Missing required field "Rules".'));
    }

    /**
     * @param array{
     *   Rules: array<ServerSideEncryptionRule|array>,
     * }|ServerSideEncryptionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ServerSideEncryptionRule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
