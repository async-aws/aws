<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings associated with S3 destination.
 */
final class S3DestinationSettings
{
    /**
     * Optional. Have MediaConvert automatically apply Amazon S3 access control for the outputs in this output group. When
     * you don't use this setting, S3 automatically applies the default access control list PRIVATE.
     */
    private $accessControl;

    /**
     * Settings for how your job outputs are encrypted as they are uploaded to Amazon S3.
     */
    private $encryption;

    /**
     * @param array{
     *   AccessControl?: null|S3DestinationAccessControl|array,
     *   Encryption?: null|S3EncryptionSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessControl = isset($input['AccessControl']) ? S3DestinationAccessControl::create($input['AccessControl']) : null;
        $this->encryption = isset($input['Encryption']) ? S3EncryptionSettings::create($input['Encryption']) : null;
    }

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

        return $payload;
    }
}
