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
     * Identifies the KMS key to use in the encryption operation. The KMS key must have a `KeyUsage` of `ENCRYPT_DECRYPT`.
     * To find the `KeyUsage` of a KMS key, use the DescribeKey operation.
     *
     * To specify a KMS key, use its key ID, key ARN, alias name, or alias ARN. When using an alias name, prefix it with
     * `"alias/"`. To specify a KMS key in a different Amazon Web Services account, you must use the key ARN or alias ARN.
     *
     * For example:
     *
     * - Key ID: `1234abcd-12ab-34cd-56ef-1234567890ab`
     * - Key ARN: `arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab`
     * - Alias name: `alias/ExampleAlias`
     * - Alias ARN: `arn:aws:kms:us-east-2:111122223333:alias/ExampleAlias`
     *
     * To get the key ID and key ARN for a KMS key, use ListKeys or DescribeKey. To get the alias name and alias ARN, use
     * ListAliases.
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
     * cryptographic operations [^1] with a symmetric encryption KMS key. The standard asymmetric encryption algorithms and
     * HMAC algorithms that KMS uses do not support an encryption context.
     *
     * ! Do not include confidential or sensitive information in this field. This field may be displayed in plaintext in
     * ! CloudTrail logs and other output.
     *
     * An *encryption context* is a collection of non-secret key-value pairs that represent additional authenticated data.
     * When you use an encryption context to encrypt data, you must specify the same (an exact case-sensitive match)
     * encryption context to decrypt the data. An encryption context is supported only on operations with symmetric
     * encryption KMS keys. On operations with symmetric encryption KMS keys, an encryption context is optional, but it is
     * strongly recommended.
     *
     * For more information, see Encryption context [^2] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/kms-cryptography.html#cryptographic-operations
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/encrypt_context.html
     *
     * @var array<string, string>|null
     */
    private $encryptionContext;

    /**
     * A list of grant tokens.
     *
     * Use a grant token when your permission to call this operation comes from a new grant that has not yet achieved
     * *eventual consistency*. For more information, see Grant token [^1] and Using a grant token [^2] in the *Key
     * Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/grants.html#grant_token
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/using-grant-token.html
     *
     * @var string[]|null
     */
    private $grantTokens;

    /**
     * Specifies the encryption algorithm that KMS will use to encrypt the plaintext message. The algorithm must be
     * compatible with the KMS key that you specify.
     *
     * This parameter is required only for asymmetric KMS keys. The default value, `SYMMETRIC_DEFAULT`, is the algorithm
     * used for symmetric encryption KMS keys. If you are using an asymmetric KMS key, we recommend RSAES_OAEP_SHA_256.
     *
     * The SM2PKE algorithm is only available in China Regions.
     *
     * @var EncryptionAlgorithmSpec::*|null
     */
    private $encryptionAlgorithm;

    /**
     * Checks if your request will succeed. `DryRun` is an optional parameter.
     *
     * To learn more about how to use this parameter, see Testing your permissions [^1] in the *Key Management Service
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/testing-permissions.html
     *
     * @var bool|null
     */
    private $dryRun;

    /**
     * @param array{
     *   KeyId?: string,
     *   Plaintext?: string,
     *   EncryptionContext?: array<string, string>|null,
     *   GrantTokens?: string[]|null,
     *   EncryptionAlgorithm?: EncryptionAlgorithmSpec::*|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->plaintext = $input['Plaintext'] ?? null;
        $this->encryptionContext = $input['EncryptionContext'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->encryptionAlgorithm = $input['EncryptionAlgorithm'] ?? null;
        $this->dryRun = $input['DryRun'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   KeyId?: string,
     *   Plaintext?: string,
     *   EncryptionContext?: array<string, string>|null,
     *   GrantTokens?: string[]|null,
     *   EncryptionAlgorithm?: EncryptionAlgorithmSpec::*|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * }|EncryptRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDryRun(): ?bool
    {
        return $this->dryRun;
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
            'Accept' => 'application/json',
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

    public function setDryRun(?bool $value): self
    {
        $this->dryRun = $value;

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

    public function setPlaintext(?string $value): self
    {
        $this->plaintext = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->keyId) {
            throw new InvalidArgument(\sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
        if (null === $v = $this->plaintext) {
            throw new InvalidArgument(\sprintf('Missing parameter "Plaintext" for "%s". The value cannot be null.', __CLASS__));
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
                throw new InvalidArgument(\sprintf('Invalid parameter "EncryptionAlgorithm" for "%s". The value "%s" is not a valid "EncryptionAlgorithmSpec".', __CLASS__, $v));
            }
            $payload['EncryptionAlgorithm'] = $v;
        }
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = (bool) $v;
        }

        return $payload;
    }
}
