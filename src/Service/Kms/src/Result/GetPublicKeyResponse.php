<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use OpenSSLAsymmetricKey;

class GetPublicKeyResponse extends Result
{
    /**
     * The Amazon Resource Name (key ARN [^1]) of the asymmetric KMS key that was used to sign the message.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $keyId;

    /**
     * The type of the of the public key that was downloaded.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/APIReference/API_GetPublicKey.html#KMS-GetPublicKey-response-KeySpec
     * @var string|null
     */
    private $keySpec;

    /**
     * The permitted use of the public key. Valid values for asymmetric key pairs are ENCRYPT_DECRYPT, SIGN_VERIFY, and KEY_AGREEMENT.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/APIReference/API_GetPublicKey.html#API_GetPublicKey_ResponseSyntax
     *
     * @var string|null
     */
    private $keyUsage;

    /**
     * The exported public key.
     * The value is a DER-encoded X.509 public key, also known as SubjectPublicKeyInfo (SPKI), as defined in RFC 5280.
     * When you use the HTTP API or the AWS CLI, the value is Base64-encoded. Otherwise, it is not Base64-encoded.
     * Type: Base64-encoded binary data object. Length Constraints: Minimum length of 1. Maximum length of 8192.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/APIReference/API_GetPublicKey.html#API_GetPublicKey_ResponseSyntax
     *
     * @var string|null
     */
    private $publicKey;

    /**
     * The key agreement algorithm used to derive a shared secret. This field is present only when the KMS key has a KeyUsage value of KEY_AGREEMENT.
     *
     * @var string|null
     */
    private $keyAgreementAlgorithms;

    /**
     * The encryption algorithms that AWS KMS supports for this key.
     *
     * @var string|null
     */
    private $encryptionAlgorithms;

    /**
     * This parameter has been deprecated.
     *
     * @var string|null
     */
    private $customerMasterKeySpec;

    /**
     * The signing algorithm that was used to sign the message.
     *
     * @var array|null
     */
    private $signingAlgorithms;

    public function getKeyId(): ?string
    {
        $this->initialize();

        return $this->keyId;
    }

    public function getSigningAlgorithms(): ?array
    {
        $this->initialize();

        return $this->signingAlgorithms;
    }

    /**
     * The type of the of the public key that was downloaded.
     */
    public function getKeySpec(): string|null
    {
        $this->initialize();

        return $this->keySpec;
    }

    /**
     * The permitted use of the public key. Valid values for asymmetric key pairs are ENCRYPT_DECRYPT, SIGN_VERIFY, and KEY_AGREEMENT.
     */
    public function getKeyUsage(): string|null
    {
        $this->initialize();

        return $this->keyUsage;
    }

    /**
     * The exported public key.
     *
     * @return  string|null
     */
    public function getPublicKey(): OpenSSLAsymmetricKey|string|null
    {
        $this->initialize();

        return $this->publicKey;
    }

    /**
     * Get the key agreement algorithm used to derive a shared secret. This field is present only when the KMS key has a KeyUsage value of KEY_AGREEMENT.
     */
    public function getKeyAgreementAlgorithms(): array|null
    {
        $this->initialize();

        return $this->keyAgreementAlgorithms;
    }

    /**
     * Get the encryption algorithms that AWS KMS supports for this key.
     */
    public function getEncryptionAlgorithms(): array|null
    {
        $this->initialize();

        return $this->encryptionAlgorithms;
    }

    /**
     * Get this parameter has been deprecated.
     */
    public function getCustomerMasterKeySpec(): string|null
    {
        $this->initialize();

        return $this->customerMasterKeySpec;
    }

    public function formarKey($derData): string
    {
        $pem = "-----BEGIN PUBLIC KEY-----\n";
        $pem .= chunk_split((string) $derData, 64, "\n");
        return $pem . "-----END PUBLIC KEY-----\n";
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();
        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->keySpec = isset($data['KeySpec']) ? (string) $data['KeySpec'] : null;
        $this->keyUsage = isset($data['KeyUsage']) ? (string) $data['KeyUsage'] : null;
        $this->publicKey = isset($data['PublicKey']) ? $this->formarKey($data['PublicKey']) : null;
        $this->keyAgreementAlgorithms = $data['KeyAgreementAlgorithms'] ?? null;
        $this->encryptionAlgorithms = $data['EncryptionAlgorithms'] ?? null;
        $this->customerMasterKeySpec = isset($data['CustomerMasterKeySpec']) ? (string) $data['CustomerMasterKeySpec'] : null;
        $this->signingAlgorithms = $data['SigningAlgorithms'] ?? null;
    }
}
