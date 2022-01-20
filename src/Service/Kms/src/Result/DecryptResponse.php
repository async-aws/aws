<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;

class DecryptResponse extends Result
{
    /**
     * The Amazon Resource Name (key ARN) of the KMS key that was used to decrypt the ciphertext.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     */
    private $keyId;

    /**
     * Decrypted plaintext data. When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded.
     * Otherwise, it is not Base64-encoded.
     */
    private $plaintext;

    /**
     * The encryption algorithm that was used to decrypt the ciphertext.
     */
    private $encryptionAlgorithm;

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
        $this->encryptionAlgorithm = isset($data['EncryptionAlgorithm']) ? (string) $data['EncryptionAlgorithm'] : null;
    }
}
