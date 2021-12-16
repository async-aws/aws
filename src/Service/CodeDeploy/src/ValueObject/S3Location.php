<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\BundleType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the location of a revision stored in Amazon S3.
 */
final class S3Location
{
    /**
     * The name of the Amazon S3 bucket where the application revision is stored.
     */
    private $bucket;

    /**
     * The name of the Amazon S3 object that represents the bundled artifacts for the application revision.
     */
    private $key;

    /**
     * The file type of the application revision. Must be one of the following:.
     */
    private $bundleType;

    /**
     * A specific version of the Amazon S3 object that represents the bundled artifacts for the application revision.
     */
    private $version;

    /**
     * The ETag of the Amazon S3 object that represents the bundled artifacts for the application revision.
     */
    private $eTag;

    /**
     * @param array{
     *   bucket?: null|string,
     *   key?: null|string,
     *   bundleType?: null|BundleType::*,
     *   version?: null|string,
     *   eTag?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bucket = $input['bucket'] ?? null;
        $this->key = $input['key'] ?? null;
        $this->bundleType = $input['bundleType'] ?? null;
        $this->version = $input['version'] ?? null;
        $this->eTag = $input['eTag'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    /**
     * @return BundleType::*|null
     */
    public function getBundleType(): ?string
    {
        return $this->bundleType;
    }

    public function getETag(): ?string
    {
        return $this->eTag;
    }

    public function getKey(): ?string
    {
        return $this->key;
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
            $payload['bucket'] = $v;
        }
        if (null !== $v = $this->key) {
            $payload['key'] = $v;
        }
        if (null !== $v = $this->bundleType) {
            if (!BundleType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "bundleType" for "%s". The value "%s" is not a valid "BundleType".', __CLASS__, $v));
            }
            $payload['bundleType'] = $v;
        }
        if (null !== $v = $this->version) {
            $payload['version'] = $v;
        }
        if (null !== $v = $this->eTag) {
            $payload['eTag'] = $v;
        }

        return $payload;
    }
}
