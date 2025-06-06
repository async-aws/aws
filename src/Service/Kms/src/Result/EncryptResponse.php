<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;

class EncryptResponse extends Result
{
    /**
     * The encrypted plaintext. When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded.
     * Otherwise, it is not Base64-encoded.
     *
     * @var string|null
     */
    private $ciphertextBlob;

    /**
     * The Amazon Resource Name (key ARN [^1]) of the KMS key that was used to encrypt the plaintext.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $keyId;

    /**
     * The encryption algorithm that was used to encrypt the plaintext.
     *
     * @var EncryptionAlgorithmSpec::*|null
     */
    private $encryptionAlgorithm;

    /**
     * The identifier of the key material used to encrypt the ciphertext. This field is present only when the operation uses
     * a symmetric encryption KMS key.
     *
     * @var string|null
     */
    private $keyMaterialId;

    public function getCiphertextBlob(): ?string
    {
        $this->initialize();

        return $this->ciphertextBlob;
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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->ciphertextBlob = isset($data['CiphertextBlob']) ? base64_decode((string) $data['CiphertextBlob']) : null;
        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->encryptionAlgorithm = isset($data['EncryptionAlgorithm']) ? (string) $data['EncryptionAlgorithm'] : null;
        $this->keyMaterialId = isset($data['KeyMaterialId']) ? (string) $data['KeyMaterialId'] : null;
    }
}
