<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;

final class EncryptRequest extends Input
{
    /**
     * Identifies the KMS key to use in the encryption operation.
     *
     * @required
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Data to be encrypted.
     *
     * @required
     *
     * @var string|null
     */
    private $plaintext;

    /**
     * Specifies the encryption context that will be used to encrypt the data. An encryption context is valid only for
     * cryptographic operations with a symmetric KMS key. The standard asymmetric encryption algorithms that KMS uses do not
     * support an encryption context.
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
     * Specifies the encryption algorithm that KMS will use to encrypt the plaintext message. The algorithm must be
     * compatible with the KMS key that you specify.
     *
     * @var EncryptionAlgorithmSpec::*|null
     */
    private $encryptionAlgorithm;

    /**
     * @param array{
     *   KeyId?: string,
     *   Plaintext?: string,
     *   EncryptionContext?: array<string, string>,
     *   GrantTokens?: string[],
     *   EncryptionAlgorithm?: EncryptionAlgorithmSpec::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->plaintext = $input['Plaintext'] ?? null;
        $this->encryptionContext = $input['EncryptionContext'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->encryptionAlgorithm = $input['EncryptionAlgorithm'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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

    public function getPlaintext(): ?string
    {
        return $this->plaintext;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.Encrypt',
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

    public function setPlaintext(?string $value): self
    {
        $this->plaintext = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->keyId) {
            throw new InvalidArgument(sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
        if (null === $v = $this->plaintext) {
            throw new InvalidArgument(sprintf('Missing parameter "Plaintext" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Plaintext'] = base64_encode($v);
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
        if (null !== $v = $this->encryptionAlgorithm) {
            if (!EncryptionAlgorithmSpec::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "EncryptionAlgorithm" for "%s". The value "%s" is not a valid "EncryptionAlgorithmSpec".', __CLASS__, $v));
            }
            $payload['EncryptionAlgorithm'] = $v;
        }

        return $payload;
    }
}
