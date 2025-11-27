<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;

class DecryptResponse extends Result
{
    /**
     * The Amazon Resource Name (key ARN [^1]) of the KMS key that was used to decrypt the ciphertext.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Decrypted plaintext data. When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded.
     * Otherwise, it is not Base64-encoded.
     *
     * If the response includes the `CiphertextForRecipient` field, the `Plaintext` field is null or empty.
     *
     * @var string|null
     */
    private $plaintext;

    /**
     * The encryption algorithm that was used to decrypt the ciphertext.
     *
     * @var EncryptionAlgorithmSpec::*|null
     */
    private $encryptionAlgorithm;

    /**
     * The plaintext data encrypted with the public key from the attestation document. This ciphertext can be decrypted only
     * by using a private key from the attested environment.
     *
     * This field is included in the response only when the `Recipient` parameter in the request includes a valid
     * attestation document from an Amazon Web Services Nitro enclave or NitroTPM. For information about the interaction
     * between KMS and Amazon Web Services Nitro Enclaves or Amazon Web Services NitroTPM, see Cryptographic attestation
     * support in KMS [^1] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/cryptographic-attestation.html
     *
     * @var string|null
     */
    private $ciphertextForRecipient;

    /**
     * The identifier of the key material used to decrypt the ciphertext. This field is present only when the operation uses
     * a symmetric encryption KMS key. This field is omitted if the request includes the `Recipient` parameter.
     *
     * @var string|null
     */
    private $keyMaterialId;

    public function getCiphertextForRecipient(): ?string
    {
        $this->initialize();

        return $this->ciphertextForRecipient;
    }

    /**
     * @return EncryptionAlgorithmSpec::*|null
     */
    public function getEncryptionAlgorithm(): ?string
    {
        $this->initialize();

        return $this->encryptionAlgorithm;
    }

    public function getKeyId(): ?string
    {
        $this->initialize();

        return $this->keyId;
    }

    public function getKeyMaterialId(): ?string
    {
        $this->initialize();

        return $this->keyMaterialId;
    }

    public function getPlaintext(): ?string
    {
        $this->initialize();

        return $this->plaintext;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->plaintext = isset($data['Plaintext']) ? base64_decode((string) $data['Plaintext']) : null;
        $this->encryptionAlgorithm = isset($data['EncryptionAlgorithm']) ? (!EncryptionAlgorithmSpec::exists((string) $data['EncryptionAlgorithm']) ? EncryptionAlgorithmSpec::UNKNOWN_TO_SDK : (string) $data['EncryptionAlgorithm']) : null;
        $this->ciphertextForRecipient = isset($data['CiphertextForRecipient']) ? base64_decode((string) $data['CiphertextForRecipient']) : null;
        $this->keyMaterialId = isset($data['KeyMaterialId']) ? (string) $data['KeyMaterialId'] : null;
    }
}
