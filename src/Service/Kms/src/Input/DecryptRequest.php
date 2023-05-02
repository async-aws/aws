<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\ValueObject\RecipientInfo;

final class DecryptRequest extends Input
{
    /**
     * Ciphertext to be decrypted. The blob includes metadata.
     *
     * @required
     *
     * @var string|null
     */
    private $ciphertextBlob;

    /**
     * Specifies the encryption context to use when decrypting the data. An encryption context is valid only for
     * cryptographic operations with a symmetric encryption KMS key. The standard asymmetric encryption algorithms and HMAC
     * algorithms that KMS uses do not support an encryption context.
     *
     * @see https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
     *
     * @var array<string, string>|null
     */
    private $encryptionContext;

    /**
     * A list of grant tokens.
     *
     * @var string[]|null
     */
    private $grantTokens;

    /**
     * Specifies the KMS key that KMS uses to decrypt the ciphertext.
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Specifies the encryption algorithm that will be used to decrypt the ciphertext. Specify the same algorithm that was
     * used to encrypt the data. If you specify a different algorithm, the `Decrypt` operation fails.
     *
     * @var EncryptionAlgorithmSpec::*|null
     */
    private $encryptionAlgorithm;

    /**
     * A signed attestation document from an Amazon Web Services Nitro enclave and the encryption algorithm to use with the
     * enclave's public key. The only valid encryption algorithm is `RSAES_OAEP_SHA_256`.
     *
     * @see https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave-how.html#term-attestdoc
     *
     * @var RecipientInfo|null
     */
    private $recipient;

    /**
     * @param array{
     *   CiphertextBlob?: string,
     *   EncryptionContext?: array<string, string>,
     *   GrantTokens?: string[],
     *   KeyId?: string,
     *   EncryptionAlgorithm?: EncryptionAlgorithmSpec::*,
     *   Recipient?: RecipientInfo|array,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ciphertextBlob = $input['CiphertextBlob'] ?? null;
        $this->encryptionContext = $input['EncryptionContext'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->keyId = $input['KeyId'] ?? null;
        $this->encryptionAlgorithm = $input['EncryptionAlgorithm'] ?? null;
        $this->recipient = isset($input['Recipient']) ? RecipientInfo::create($input['Recipient']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCiphertextBlob(): ?string
    {
        return $this->ciphertextBlob;
    }

    /**
     * @return EncryptionAlgorithmSpec::*|null
     */
    public function getEncryptionAlgorithm(): ?string
    {
        return $this->encryptionAlgorithm;
    }

    /**
     * @return array<string, string>
     */
    public function getEncryptionContext(): array
    {
        return $this->encryptionContext ?? [];
    }

    /**
     * @return string[]
     */
    public function getGrantTokens(): array
    {
        return $this->grantTokens ?? [];
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    public function getRecipient(): ?RecipientInfo
    {
        return $this->recipient;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.Decrypt',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCiphertextBlob(?string $value): self
    {
        $this->ciphertextBlob = $value;

        return $this;
    }

    /**
     * @param EncryptionAlgorithmSpec::*|null $value
     */
    public function setEncryptionAlgorithm(?string $value): self
    {
        $this->encryptionAlgorithm = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setEncryptionContext(array $value): self
    {
        $this->encryptionContext = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setGrantTokens(array $value): self
    {
        $this->grantTokens = $value;

        return $this;
    }

    public function setKeyId(?string $value): self
    {
        $this->keyId = $value;

        return $this;
    }

    public function setRecipient(?RecipientInfo $value): self
    {
        $this->recipient = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ciphertextBlob) {
            throw new InvalidArgument(sprintf('Missing parameter "CiphertextBlob" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CiphertextBlob'] = base64_encode($v);
        if (null !== $v = $this->encryptionContext) {
            if (empty($v)) {
                $payload['EncryptionContext'] = new \stdClass();
            } else {
                $payload['EncryptionContext'] = [];
                foreach ($v as $name => $mv) {
                    $payload['EncryptionContext'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->grantTokens) {
            $index = -1;
            $payload['GrantTokens'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GrantTokens'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->keyId) {
            $payload['KeyId'] = $v;
        }
        if (null !== $v = $this->encryptionAlgorithm) {
            if (!EncryptionAlgorithmSpec::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "EncryptionAlgorithm" for "%s". The value "%s" is not a valid "EncryptionAlgorithmSpec".', __CLASS__, $v));
            }
            $payload['EncryptionAlgorithm'] = $v;
        }
        if (null !== $v = $this->recipient) {
            $payload['Recipient'] = $v->requestBody();
        }

        return $payload;
    }
}
