<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides the S3 bucket name and object name.
 *
 * The region for the S3 bucket containing the S3 object must match the region you use for Amazon Rekognition
 * operations.
 *
 * For Amazon Rekognition to process an S3 object, the user must have permission to access the S3 object. For more
 * information, see How Amazon Rekognition works with IAM in the Amazon Rekognition Developer Guide.
 */
final class S3Object
{
    /**
     * Name of the S3 bucket.
     *
     * @var string|null
     */
    private $bucket;

    /**
     * S3 object key name.
     *
     * @var string|null
     */
    private $name;

    /**
     * If the bucket is versioning enabled, you can specify the object version.
     *
     * @var string|null
     */
    private $version;

    /**
     * @param array{
     *   Bucket?: string|null,
     *   Name?: string|null,
     *   Version?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->version = $input['Version'] ?? null;
    }

    /**
     * @param array{
     *   Bucket?: string|null,
     *   Name?: string|null,
     *   Version?: string|null,
     * }|S3Object $input
     */
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
