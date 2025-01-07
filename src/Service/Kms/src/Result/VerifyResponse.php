<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class VerifyResponse extends Result
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
     * The signing algorithm that was used to sign the message.
     *
     * @var SigningAlgorithmSpec::*|null
     */
    private $signingAlgorithm;

    private bool $signatureValid = false;

    public function getKeyId(): ?string
    {
        $this->initialize();

        return $this->keyId;
    }

    /**
     * @return SigningAlgorithmSpec::*|null
     */
    public function getSigningAlgorithm(): ?string
    {
        $this->initialize();

        return $this->signingAlgorithm;
    }

    public function isSignatureValid(): bool
    {
        $this->initialize();

        return $this->signatureValid;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();
        $this->keyId = isset($data['KeyId']) ? (string) $data['KeyId'] : null;
        $this->signatureValid = (bool) $data['SignatureValid'];
        $this->signingAlgorithm = isset($data['SigningAlgorithm']) ? (string) $data['SigningAlgorithm'] : null;
    }
}
