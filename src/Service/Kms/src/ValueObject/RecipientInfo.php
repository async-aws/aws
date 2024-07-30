<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kms\Enum\KeyEncryptionMechanism;

/**
 * Contains information about the party that receives the response from the API operation.
 *
 * This data type is designed to support Amazon Web Services Nitro Enclaves, which lets you create an isolated compute
 * environment in Amazon EC2. For information about the interaction between KMS and Amazon Web Services Nitro Enclaves,
 * see How Amazon Web Services Nitro Enclaves uses KMS [^1] in the *Key Management Service Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/services-nitro-enclaves.html
 */
final class RecipientInfo
{
    /**
     * The encryption algorithm that KMS should use with the public key for an Amazon Web Services Nitro Enclave to encrypt
     * plaintext values for the response. The only valid value is `RSAES_OAEP_SHA_256`.
     *
     * @var KeyEncryptionMechanism::*|null
     */
    private $keyEncryptionAlgorithm;

    /**
     * The attestation document for an Amazon Web Services Nitro Enclave. This document includes the enclave's public key.
     *
     * @var string|null
     */
    private $attestationDocument;

    /**
     * @param array{
     *   KeyEncryptionAlgorithm?: null|KeyEncryptionMechanism::*,
     *   AttestationDocument?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->keyEncryptionAlgorithm = $input['KeyEncryptionAlgorithm'] ?? null;
        $this->attestationDocument = $input['AttestationDocument'] ?? null;
    }

    /**
     * @param array{
     *   KeyEncryptionAlgorithm?: null|KeyEncryptionMechanism::*,
     *   AttestationDocument?: null|string,
     * }|RecipientInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttestationDocument(): ?string
    {
        return $this->attestationDocument;
    }

    /**
     * @return KeyEncryptionMechanism::*|null
     */
    public function getKeyEncryptionAlgorithm(): ?string
    {
        return $this->keyEncryptionAlgorithm;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->keyEncryptionAlgorithm) {
            if (!KeyEncryptionMechanism::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "KeyEncryptionAlgorithm" for "%s". The value "%s" is not a valid "KeyEncryptionMechanism".', __CLASS__, $v));
            }
            $payload['KeyEncryptionAlgorithm'] = $v;
        }
        if (null !== $v = $this->attestationDocument) {
            $payload['AttestationDocument'] = base64_encode($v);
        }

        return $payload;
    }
}
