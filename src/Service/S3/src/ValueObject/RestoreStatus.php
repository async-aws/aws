<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Specifies the restoration status of an object. Objects in certain storage classes must be restored before they can be
 * retrieved. For more information about these storage classes and how to work with archived objects, see Working with
 * archived objects [^1] in the *Amazon S3 User Guide*.
 *
 * > This functionality is not supported for directory buckets. Only the S3 Express One Zone storage class is supported
 * > by directory buckets to store objects.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/archived-objects.html
 */
final class RestoreStatus
{
    /**
     * Specifies whether the object is currently being restored. If the object restoration is in progress, the header
     * returns the value `TRUE`. For example:
     *
     * `x-amz-optional-object-attributes: IsRestoreInProgress="true"`
     *
     * If the object restoration has completed, the header returns the value `FALSE`. For example:
     *
     * `x-amz-optional-object-attributes: IsRestoreInProgress="false", RestoreExpiryDate="2012-12-21T00:00:00.000Z"`
     *
     * If the object hasn't been restored, there is no header response.
     *
     * @var bool|null
     */
    private $isRestoreInProgress;

    /**
     * Indicates when the restored copy will expire. This value is populated only if the object has already been restored.
     * For example:
     *
     * `x-amz-optional-object-attributes: IsRestoreInProgress="false", RestoreExpiryDate="2012-12-21T00:00:00.000Z"`
     *
     * @var \DateTimeImmutable|null
     */
    private $restoreExpiryDate;

    /**
     * @param array{
     *   IsRestoreInProgress?: null|bool,
     *   RestoreExpiryDate?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->isRestoreInProgress = $input['IsRestoreInProgress'] ?? null;
        $this->restoreExpiryDate = $input['RestoreExpiryDate'] ?? null;
    }

    /**
     * @param array{
     *   IsRestoreInProgress?: null|bool,
     *   RestoreExpiryDate?: null|\DateTimeImmutable,
     * }|RestoreStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIsRestoreInProgress(): ?bool
    {
        return $this->isRestoreInProgress;
    }

    public function getRestoreExpiryDate(): ?\DateTimeImmutable
    {
        return $this->restoreExpiryDate;
    }
}
