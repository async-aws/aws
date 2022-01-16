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
     */
    private $ciphertextBlob;

    /**
     * The Amazon Resource Name (key ARN) of the KMS key that was used to encrypt the plaintext.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     */
    private $keyId;

    /**
     * The encryption algorithm that was used to encrypt the plaintext.
     */
    private $encryptionAlgorithm;

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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->ciphertextBlob = isset($data['CiphertextBlob']) ? base64_decode((string) $data['CiphertextBlob']) : null;
        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->encryptionAlgorithm = isset($data['EncryptionAlgorithm']) ? (string) $data['EncryptionAlgorithm'] : null;
    }
}
