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
    private $Bucket;

    /**
     * S3 object key name.
     */
    private $Name;

    /**
     * If the bucket is versioning enabled, you can specify the object version.
     */
    private $Version;

    /**
     * @param array{
     *   Bucket?: null|string,
     *   Name?: null|string,
     *   Version?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Name = $input['Name'] ?? null;
        $this->Version = $input['Version'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getVersion(): ?string
    {
        return $this->Version;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Bucket) {
            $payload['Bucket'] = $v;
        }
        if (null !== $v = $this->Name) {
            $payload['Name'] = $v;
        }
        if (null !== $v = $this->Version) {
            $payload['Version'] = $v;
        }

        return $payload;
    }
}
