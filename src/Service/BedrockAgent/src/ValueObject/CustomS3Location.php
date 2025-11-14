<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about the Amazon S3 location of the file containing the content to ingest into a knowledge base
 * connected to a custom data source.
 */
final class CustomS3Location
{
    /**
     * The S3 URI of the file containing the content to ingest.
     *
     * @var string
     */
    private $uri;

    /**
     * The identifier of the Amazon Web Services account that owns the S3 bucket containing the content to ingest.
     *
     * @var string|null
     */
    private $bucketOwnerAccountId;

    /**
     * @param array{
     *   uri: string,
     *   bucketOwnerAccountId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->uri = $input['uri'] ?? $this->throwException(new InvalidArgument('Missing required field "uri".'));
        $this->bucketOwnerAccountId = $input['bucketOwnerAccountId'] ?? null;
    }

    /**
     * @param array{
     *   uri: string,
     *   bucketOwnerAccountId?: string|null,
     * }|CustomS3Location $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucketOwnerAccountId(): ?string
    {
        return $this->bucketOwnerAccountId;
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
        if (null !== $v = $this->bucketOwnerAccountId) {
            $payload['bucketOwnerAccountId'] = $v;
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
