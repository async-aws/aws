<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An Amazon S3 location.
 */
final class S3Location
{
    /**
     * The location's URI. For example, `s3://my-bucket/chunk-processor/`.
     *
     * @var string
     */
    private $uri;

    /**
     * @param array{
     *   uri: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->uri = $input['uri'] ?? $this->throwException(new InvalidArgument('Missing required field "uri".'));
    }

    /**
     * @param array{
     *   uri: string,
     * }|S3Location $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->uri;
        $payload['uri'] = $v;

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
