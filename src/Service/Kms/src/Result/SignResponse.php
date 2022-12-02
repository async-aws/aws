<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

class SignResponse extends Result
{
    /**
     * The Amazon Resource Name (key ARN) of the asymmetric KMS key that was used to sign the message.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     */
    private $keyId;

    /**
     * The cryptographic signature that was generated for the message.
     */
    private $signature;

    /**
     * The signing algorithm that was used to sign the message.
     */
    private $signingAlgorithm;

    public function getKeyId(): ?string
    {
        $this->initialize();

        return $this->keyId;
    }

    public function getSignature(): ?string
    {
        $this->initialize();

        return $this->signature;
    }

    /**
     * @return SigningAlgorithmSpec::*|null
     */
    public function getSigningAlgorithm(): ?string
    {
        $this->initialize();

        return $this->signingAlgorithm;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->signature = isset($data['Signature']) ? base64_decode((string) $data['Signature']) : null;
        $this->signingAlgorithm = isset($data['SigningAlgorithm']) ? (string) $data['SigningAlgorithm'] : null;
    }
}
