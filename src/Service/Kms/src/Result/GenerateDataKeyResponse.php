<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GenerateDataKeyResponse extends Result
{
    /**
     * The encrypted copy of the data key. When you use the HTTP API or the Amazon Web Services CLI, the value is
     * Base64-encoded. Otherwise, it is not Base64-encoded.
     */
    private $ciphertextBlob;

    /**
     * The plaintext data key. When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded.
     * Otherwise, it is not Base64-encoded. Use this data key to encrypt your data outside of KMS. Then, remove it from
     * memory as soon as possible.
     */
    private $plaintext;

    /**
     * The Amazon Resource Name (key ARN) of the KMS key that encrypted the data key.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     */
    private $keyId;

    /**
     * The plaintext data key encrypted with the public key from the Nitro enclave. This ciphertext can be decrypted only by
     * using a private key in the Nitro enclave.
     */
    private $ciphertextForRecipient;

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
    }
}
