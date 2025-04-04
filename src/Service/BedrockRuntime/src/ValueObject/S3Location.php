<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A storage location in an S3 bucket.
 */
final class S3Location
{
    /**
     * An object URI starting with `s3://`.
     *
     * @var string
     */
    private $uri;

    /**
     * If the bucket belongs to another AWS account, specify that account's ID.
     *
     * @var string|null
     */
    private $bucketOwner;

    /**
     * @param array{
     *   uri: string,
     *   bucketOwner?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->uri = $input['uri'] ?? $this->throwException(new InvalidArgument('Missing required field "uri".'));
        $this->bucketOwner = $input['bucketOwner'] ?? null;
    }

    /**
     * @param array{
     *   uri: string,
     *   bucketOwner?: null|string,
     * }|S3Location $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucketOwner(): ?string
    {
        return $this->bucketOwner;
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
        if (null !== $v = $this->bucketOwner) {
            $payload['bucketOwner'] = $v;
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
