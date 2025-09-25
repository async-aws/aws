<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GenerateDataKeyResponse extends Result
{
    /**
     * The encrypted copy of the data key. When you use the HTTP API or the Amazon Web Services CLI, the value is
     * Base64-encoded. Otherwise, it is not Base64-encoded.
     *
     * @var string|null
     */
    private $ciphertextBlob;

    /**
     * The plaintext data key. When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded.
     * Otherwise, it is not Base64-encoded. Use this data key to encrypt your data outside of KMS. Then, remove it from
     * memory as soon as possible.
     *
     * If the response includes the `CiphertextForRecipient` field, the `Plaintext` field is null or empty.
     *
     * @var string|null
     */
    private $plaintext;

    /**
     * The Amazon Resource Name (key ARN [^1]) of the KMS key that encrypted the data key.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $keyId;

    /**
     * The plaintext data key encrypted with the public key from the attestation document. This ciphertext can be decrypted
     * only by using a private key from the attested environment.
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
     * The identifier of the key material used to encrypt the data key. This field is omitted if the request includes the
     * `Recipient` parameter.
     *
     * @var string|null
     */
    private $keyMaterialId;

    public function getCiphertextBlob(): ?string
    {
        $this->initialize();

        return $this->ciphertextBlob;
    }

    public function getCiphertextForRecipient(): ?string
    {
        $this->initialize();

        return $this->ciphertextForRecipient;
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

        $this->ciphertextBlob = isset($data['CiphertextBlob']) ? base64_decode((string) $data['CiphertextBlob']) : null;
        $this->plaintext = isset($data['Plaintext']) ? base64_decode((string) $data['Plaintext']) : null;
        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->ciphertextForRecipient = isset($data['CiphertextForRecipient']) ? base64_decode((string) $data['CiphertextForRecipient']) : null;
        $this->keyMaterialId = isset($data['KeyMaterialId']) ? (string) $data['KeyMaterialId'] : null;
    }
}
