<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kms\Enum\KeyEncryptionMechanism;

/**
 * A signed attestation document from an Amazon Web Services Nitro enclave and the encryption algorithm to use with the
 * enclave's public key. The only valid encryption algorithm is `RSAES_OAEP_SHA_256`.
 * This parameter only supports attestation documents for Amazon Web Services Nitro Enclaves. To include this parameter,
 * use the Amazon Web Services Nitro Enclaves SDK or any Amazon Web Services SDK.
 * When you use this parameter, instead of returning the plaintext data, KMS encrypts the plaintext data with the public
 * key in the attestation document, and returns the resulting ciphertext in the `CiphertextForRecipient` field in the
 * response. This ciphertext can be decrypted only with the private key in the enclave. The `Plaintext` field in the
 * response is null or empty.
 * For information about the interaction between KMS and Amazon Web Services Nitro Enclaves, see How Amazon Web Services
 * Nitro Enclaves uses KMS in the *Key Management Service Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave-how.html#term-attestdoc
 * @see https://docs.aws.amazon.com/enclaves/latest/user/developing-applications.html#sdk
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/services-nitro-enclaves.html
 */
final class RecipientInfo
{
    /**
     * The encryption algorithm that KMS should use with the public key for an Amazon Web Services Nitro Enclave to encrypt
     * plaintext values for the response. The only valid value is `RSAES_OAEP_SHA_256`.
     */
    private $keyEncryptionAlgorithm;

    /**
     * The attestation document for an Amazon Web Services Nitro Enclave. This document includes the enclave's public key.
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
                throw new InvalidArgument(sprintf('Invalid parameter "KeyEncryptionAlgorithm" for "%s". The value "%s" is not a valid "KeyEncryptionMechanism".', __CLASS__, $v));
            }
            $payload['KeyEncryptionAlgorithm'] = $v;
        }
        if (null !== $v = $this->attestationDocument) {
            $payload['AttestationDocument'] = base64_encode($v);
        }

        return $payload;
    }
}
