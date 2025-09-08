<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\DecryptionMode;

/**
 * Settings for decrypting any input files that you encrypt before you upload them to Amazon S3. MediaConvert can
 * decrypt files only when you use AWS Key Management Service (KMS) to encrypt the data key that you use to encrypt your
 * content.
 */
final class InputDecryptionSettings
{
    /**
     * Specify the encryption mode that you used to encrypt your input files.
     *
     * @var DecryptionMode::*|null
     */
    private $decryptionMode;

    /**
     * Warning! Don't provide your encryption key in plaintext. Your job settings could be intercepted, making your
     * encrypted content vulnerable. Specify the encrypted version of the data key that you used to encrypt your content.
     * The data key must be encrypted by AWS Key Management Service (KMS). The key can be 128, 192, or 256 bits.
     *
     * @var string|null
     */
    private $encryptedDecryptionKey;

    /**
     * Specify the initialization vector that you used when you encrypted your content before uploading it to Amazon S3. You
     * can use a 16-byte initialization vector with any encryption mode. Or, you can use a 12-byte initialization vector
     * with GCM or CTR. MediaConvert accepts only initialization vectors that are base64-encoded.
     *
     * @var string|null
     */
    private $initializationVector;

    /**
     * Specify the AWS Region for AWS Key Management Service (KMS) that you used to encrypt your data key, if that Region is
     * different from the one you are using for AWS Elemental MediaConvert.
     *
     * @var string|null
     */
    private $kmsKeyRegion;

    /**
     * @param array{
     *   DecryptionMode?: DecryptionMode::*|null,
     *   EncryptedDecryptionKey?: string|null,
     *   InitializationVector?: string|null,
     *   KmsKeyRegion?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->decryptionMode = $input['DecryptionMode'] ?? null;
        $this->encryptedDecryptionKey = $input['EncryptedDecryptionKey'] ?? null;
        $this->initializationVector = $input['InitializationVector'] ?? null;
        $this->kmsKeyRegion = $input['KmsKeyRegion'] ?? null;
    }

    /**
     * @param array{
     *   DecryptionMode?: DecryptionMode::*|null,
     *   EncryptedDecryptionKey?: string|null,
     *   InitializationVector?: string|null,
     *   KmsKeyRegion?: string|null,
     * }|InputDecryptionSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DecryptionMode::*|null
     */
    public function getDecryptionMode(): ?string
    {
        return $this->decryptionMode;
    }

    public function getEncryptedDecryptionKey(): ?string
    {
        return $this->encryptedDecryptionKey;
    }

    public function getInitializationVector(): ?string
    {
        return $this->initializationVector;
    }

    public function getKmsKeyRegion(): ?string
    {
        return $this->kmsKeyRegion;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->decryptionMode) {
            if (!DecryptionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "decryptionMode" for "%s". The value "%s" is not a valid "DecryptionMode".', __CLASS__, $v));
            }
            $payload['decryptionMode'] = $v;
        }
        if (null !== $v = $this->encryptedDecryptionKey) {
            $payload['encryptedDecryptionKey'] = $v;
        }
        if (null !== $v = $this->initializationVector) {
            $payload['initializationVector'] = $v;
        }
        if (null !== $v = $this->kmsKeyRegion) {
            $payload['kmsKeyRegion'] = $v;
        }

        return $payload;
    }
}
