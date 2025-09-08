<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\BundleType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the location of application artifacts stored in Amazon S3.
 */
final class S3Location
{
    /**
     * The name of the Amazon S3 bucket where the application revision is stored.
     *
     * @var string|null
     */
    private $bucket;

    /**
     * The name of the Amazon S3 object that represents the bundled artifacts for the application revision.
     *
     * @var string|null
     */
    private $key;

    /**
     * The file type of the application revision. Must be one of the following:
     *
     * - `tar`: A tar archive file.
     * - `tgz`: A compressed tar archive file.
     * - `zip`: A zip archive file.
     * - `YAML`: A YAML-formatted file.
     * - `JSON`: A JSON-formatted file.
     *
     * @var BundleType::*|null
     */
    private $bundleType;

    /**
     * A specific version of the Amazon S3 object that represents the bundled artifacts for the application revision.
     *
     * If the version is not specified, the system uses the most recent version by default.
     *
     * @var string|null
     */
    private $version;

    /**
     * The ETag of the Amazon S3 object that represents the bundled artifacts for the application revision.
     *
     * If the ETag is not specified as an input parameter, ETag validation of the object is skipped.
     *
     * @var string|null
     */
    private $eTag;

    /**
     * @param array{
     *   bucket?: string|null,
     *   key?: string|null,
     *   bundleType?: BundleType::*|null,
     *   version?: string|null,
     *   eTag?: string|null,
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

    /**
     * @param array{
     *   bucket?: string|null,
     *   key?: string|null,
     *   bundleType?: BundleType::*|null,
     *   version?: string|null,
     *   eTag?: string|null,
     * }|S3Location $input
     */
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
                throw new InvalidArgument(\sprintf('Invalid parameter "bundleType" for "%s". The value "%s" is not a valid "BundleType".', __CLASS__, $v));
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
