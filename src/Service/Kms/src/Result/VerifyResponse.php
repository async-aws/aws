<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

class VerifyResponse extends Result
{
    /**
     * The Amazon Resource Name (key ARN [^1]) of the asymmetric KMS key that was used to verify the signature.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $keyId;

    /**
     * A Boolean value that indicates whether the signature was verified. A value of `True` indicates that the `Signature`
     * was produced by signing the `Message` with the specified `KeyID` and `SigningAlgorithm.` If the signature is not
     * verified, the `Verify` operation fails with a `KMSInvalidSignatureException` exception.
     *
     * @var bool|null
     */
    private $signatureValid;

    /**
     * The signing algorithm that was used to verify the signature.
     *
     * @var SigningAlgorithmSpec::*|string|null
     */
    private $signingAlgorithm;

    public function getKeyId(): ?string
    {
        $this->initialize();

        return $this->keyId;
    }

    public function getSignatureValid(): ?bool
    {
        $this->initialize();

        return $this->signatureValid;
    }

    /**
     * @return SigningAlgorithmSpec::*|string|null
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
        $this->signatureValid = isset($data['SignatureValid']) ? filter_var($data['SignatureValid'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->signingAlgorithm = isset($data['SigningAlgorithm']) ? (string) $data['SigningAlgorithm'] : null;
    }
}
