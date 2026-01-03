<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\S3StorageClass;

/**
 * Settings associated with S3 destination.
 */
final class S3DestinationSettings
{
    /**
     * Optional. Have MediaConvert automatically apply Amazon S3 access control for the outputs in this output group. When
     * you don't use this setting, S3 automatically applies the default access control list PRIVATE.
     *
     * @var S3DestinationAccessControl|null
     */
    private $accessControl;

    /**
     * Settings for how your job outputs are encrypted as they are uploaded to Amazon S3.
     *
     * @var S3EncryptionSettings|null
     */
    private $encryption;

    /**
     * Specify the S3 storage class to use for this output. To use your destination's default storage class: Keep the
     * default value, Not set. For more information about S3 storage classes, see
     * https://docs.aws.amazon.com/AmazonS3/latest/userguide/storage-class-intro.html.
     *
     * @var S3StorageClass::*|null
     */
    private $storageClass;

    /**
     * @param array{
     *   AccessControl?: S3DestinationAccessControl|array|null,
     *   Encryption?: S3EncryptionSettings|array|null,
     *   StorageClass?: S3StorageClass::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessControl = isset($input['AccessControl']) ? S3DestinationAccessControl::create($input['AccessControl']) : null;
        $this->encryption = isset($input['Encryption']) ? S3EncryptionSettings::create($input['Encryption']) : null;
        $this->storageClass = $input['StorageClass'] ?? null;
    }

    /**
     * @param array{
     *   AccessControl?: S3DestinationAccessControl|array|null,
     *   Encryption?: S3EncryptionSettings|array|null,
     *   StorageClass?: S3StorageClass::*|null,
     * }|S3DestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessControl(): ?S3DestinationAccessControl
    {
        return $this->accessControl;
    }

    public function getEncryption(): ?S3EncryptionSettings
    {
        return $this->encryption;
    }

    /**
     * @return S3StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->accessControl) {
            $payload['accessControl'] = $v->requestBody();
        }
        if (null !== $v = $this->encryption) {
            $payload['encryption'] = $v->requestBody();
        }
        if (null !== $v = $this->storageClass) {
            if (!S3StorageClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "storageClass" for "%s". The value "%s" is not a valid "S3StorageClass".', __CLASS__, $v));
            }
            $payload['storageClass'] = $v;
        }

        return $payload;
    }
}
