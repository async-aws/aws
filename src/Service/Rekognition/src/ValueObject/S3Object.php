<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Identifies an S3 object as the image source.
 */
final class S3Object
{
    /**
     * Name of the S3 bucket.
     */
    private $bucket;

    /**
     * S3 object key name.
     */
    private $name;

    /**
     * If the bucket is versioning enabled, you can specify the object version.
     */
    private $version;

    /**
     * @param array{
     *   Bucket?: null|string,
     *   Name?: null|string,
     *   Version?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->version = $input['Version'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bucket) {
            $payload['Bucket'] = $v;
        }
        if (null !== $v = $this->name) {
            $payload['Name'] = $v;
        }
        if (null !== $v = $this->version) {
            $payload['Version'] = $v;
        }

        return $payload;
    }
}
