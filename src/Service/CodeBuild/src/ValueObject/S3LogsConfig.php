<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\BucketOwnerAccess;
use AsyncAws\CodeBuild\Enum\LogsConfigStatusType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about S3 logs for a build project.
 */
final class S3LogsConfig
{
    /**
     * The current status of the S3 build logs. Valid values are:.
     *
     * - `ENABLED`: S3 build logs are enabled for this build project.
     * -
     * - `DISABLED`: S3 build logs are not enabled for this build project.
     */
    private $status;

    /**
     * The ARN of an S3 bucket and the path prefix for S3 logs. If your Amazon S3 bucket name is `my-bucket`, and your path
     * prefix is `build-log`, then acceptable formats are `my-bucket/build-log` or `arn:aws:s3:::my-bucket/build-log`.
     */
    private $location;

    /**
     * Set to true if you do not want your S3 build log output encrypted. By default S3 build logs are encrypted.
     */
    private $encryptionDisabled;

    private $bucketOwnerAccess;

    /**
     * @param array{
     *   status: LogsConfigStatusType::*,
     *   location?: null|string,
     *   encryptionDisabled?: null|bool,
     *   bucketOwnerAccess?: null|BucketOwnerAccess::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['status'] ?? null;
        $this->location = $input['location'] ?? null;
        $this->encryptionDisabled = $input['encryptionDisabled'] ?? null;
        $this->bucketOwnerAccess = $input['bucketOwnerAccess'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BucketOwnerAccess::*|null
     */
    public function getBucketOwnerAccess(): ?string
    {
        return $this->bucketOwnerAccess;
    }

    public function getEncryptionDisabled(): ?bool
    {
        return $this->encryptionDisabled;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @return LogsConfigStatusType::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->status) {
            throw new InvalidArgument(sprintf('Missing parameter "status" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!LogsConfigStatusType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "LogsConfigStatusType".', __CLASS__, $v));
        }
        $payload['status'] = $v;
        if (null !== $v = $this->location) {
            $payload['location'] = $v;
        }
        if (null !== $v = $this->encryptionDisabled) {
            $payload['encryptionDisabled'] = (bool) $v;
        }
        if (null !== $v = $this->bucketOwnerAccess) {
            if (!BucketOwnerAccess::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "bucketOwnerAccess" for "%s". The value "%s" is not a valid "BucketOwnerAccess".', __CLASS__, $v));
            }
            $payload['bucketOwnerAccess'] = $v;
        }

        return $payload;
    }
}
