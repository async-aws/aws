<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

class SignResponse extends Result
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
     * The cryptographic signature that was generated for the message.
     *
     * - When used with the supported RSA signing algorithms, the encoding of this value is defined by PKCS #1 in RFC 8017
     *   [^1].
     * - When used with the `ECDSA_SHA_256`, `ECDSA_SHA_384`, or `ECDSA_SHA_512` signing algorithms, this value is a
     *   DER-encoded object as defined by ANSI X9.62–2005 and RFC 3279 Section 2.2.3 [^2]. This is the most commonly used
     *   signature format and is appropriate for most uses.
     *
     * When you use the HTTP API or the Amazon Web Services CLI, the value is Base64-encoded. Otherwise, it is not
     * Base64-encoded.
     *
     * [^1]: https://tools.ietf.org/html/rfc8017
     * [^2]: https://tools.ietf.org/html/rfc3279#section-2.2.3
     *
     * @var string|null
     */
    private $signature;

    /**
     * The signing algorithm that was used to sign the message.
     *
     * @var SigningAlgorithmSpec::*|string|null
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
        $this->signature = isset($data['Signature']) ? base64_decode((string) $data['Signature']) : null;
        $this->signingAlgorithm = isset($data['SigningAlgorithm']) ? (string) $data['SigningAlgorithm'] : null;
    }
}
