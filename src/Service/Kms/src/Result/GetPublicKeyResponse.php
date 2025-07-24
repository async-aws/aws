<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\CustomerMasterKeySpec;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\KeyAgreementAlgorithmSpec;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

class GetPublicKeyResponse extends Result
{
    /**
     * The Amazon Resource Name (key ARN [^1]) of the asymmetric KMS key from which the public key was downloaded.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $keyId;

    /**
     * The exported public key.
     *
     * The value is a DER-encoded X.509 public key, also known as `SubjectPublicKeyInfo` (SPKI), as defined in RFC 5280
     * [^1]. When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded. Otherwise, it is not
     * Base64-encoded.
     *
     * [^1]: https://tools.ietf.org/html/rfc5280
     *
     * @var string|null
     */
    private $publicKey;

    /**
     * Instead, use the `KeySpec` field in the `GetPublicKey` response.
     *
     * The `KeySpec` and `CustomerMasterKeySpec` fields have the same value. We recommend that you use the `KeySpec` field
     * in your code. However, to avoid breaking changes, KMS supports both fields.
     *
     * @var CustomerMasterKeySpec::*|string|null
     */
    private $customerMasterKeySpec;

    /**
     * The type of the of the public key that was downloaded.
     *
     * @var KeySpec::*|string|null
     */
    private $keySpec;

    /**
     * The permitted use of the public key. Valid values for asymmetric key pairs are `ENCRYPT_DECRYPT`, `SIGN_VERIFY`, and
     * `KEY_AGREEMENT`.
     *
     * This information is critical. For example, if a public key with `SIGN_VERIFY` key usage encrypts data outside of KMS,
     * the ciphertext cannot be decrypted.
     *
     * @var KeyUsageType::*|string|null
     */
    private $keyUsage;

    /**
     * The encryption algorithms that KMS supports for this key.
     *
     * This information is critical. If a public key encrypts data outside of KMS by using an unsupported encryption
     * algorithm, the ciphertext cannot be decrypted.
     *
     * This field appears in the response only when the `KeyUsage` of the public key is `ENCRYPT_DECRYPT`.
     *
     * @var list<EncryptionAlgorithmSpec::*|string>
     */
    private $encryptionAlgorithms;

    /**
     * The signing algorithms that KMS supports for this key.
     *
     * This field appears in the response only when the `KeyUsage` of the public key is `SIGN_VERIFY`.
     *
     * @var list<SigningAlgorithmSpec::*|string>
     */
    private $signingAlgorithms;

    /**
     * The key agreement algorithm used to derive a shared secret. This field is present only when the KMS key has a
     * `KeyUsage` value of `KEY_AGREEMENT`.
     *
     * @var list<KeyAgreementAlgorithmSpec::*|string>
     */
    private $keyAgreementAlgorithms;

    /**
     * @deprecated
     *
     * @return CustomerMasterKeySpec::*|string|null
     */
    public function getCustomerMasterKeySpec(): ?string
    {
        @trigger_error(\sprintf('The property "customerMasterKeySpec" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->initialize();

        return $this->customerMasterKeySpec;
    }

    /**
     * @return list<EncryptionAlgorithmSpec::*|string>
     */
    public function getEncryptionAlgorithms(): array
    {
        $this->initialize();

        return $this->encryptionAlgorithms;
    }

    /**
     * @return list<KeyAgreementAlgorithmSpec::*|string>
     */
    public function getKeyAgreementAlgorithms(): array
    {
        $this->initialize();

        return $this->keyAgreementAlgorithms;
    }

    public function getKeyId(): ?string
    {
        $this->initialize();

        return $this->keyId;
    }

    /**
     * @return KeySpec::*|string|null
     */
    public function getKeySpec(): ?string
    {
        $this->initialize();

        return $this->keySpec;
    }

    /**
     * @return KeyUsageType::*|string|null
     */
    public function getKeyUsage(): ?string
    {
        $this->initialize();

        return $this->keyUsage;
    }

    public function getPublicKey(): ?string
    {
        $this->initialize();

        return $this->publicKey;
    }

    /**
     * @return list<SigningAlgorithmSpec::*|string>
     */
    public function getSigningAlgorithms(): array
    {
        $this->initialize();

        return $this->signingAlgorithms;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->publicKey = isset($data['PublicKey']) ? base64_decode((string) $data['PublicKey']) : null;
        $this->customerMasterKeySpec = isset($data['CustomerMasterKeySpec']) ? (string) $data['CustomerMasterKeySpec'] : null;
        $this->keySpec = isset($data['KeySpec']) ? (string) $data['KeySpec'] : null;
        $this->keyUsage = isset($data['KeyUsage']) ? (string) $data['KeyUsage'] : null;
        $this->encryptionAlgorithms = empty($data['EncryptionAlgorithms']) ? [] : $this->populateResultEncryptionAlgorithmSpecList($data['EncryptionAlgorithms']);
        $this->signingAlgorithms = empty($data['SigningAlgorithms']) ? [] : $this->populateResultSigningAlgorithmSpecList($data['SigningAlgorithms']);
        $this->keyAgreementAlgorithms = empty($data['KeyAgreementAlgorithms']) ? [] : $this->populateResultKeyAgreementAlgorithmSpecList($data['KeyAgreementAlgorithms']);
    }

    /**
     * @return list<EncryptionAlgorithmSpec::*|string>
     */
    private function populateResultEncryptionAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return list<KeyAgreementAlgorithmSpec::*|string>
     */
    private function populateResultKeyAgreementAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return list<SigningAlgorithmSpec::*|string>
     */
    private function populateResultSigningAlgorithmSpecList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
