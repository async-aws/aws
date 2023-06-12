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
     * cryptographic operations [^1] with a symmetric encryption KMS key. The standard asymmetric encryption algorithms and
     * HMAC algorithms that KMS uses do not support an encryption context.
     *
     * An *encryption context* is a collection of non-secret key-value pairs that represent additional authenticated data.
     * When you use an encryption context to encrypt data, you must specify the same (an exact case-sensitive match)
     * encryption context to decrypt the data. An encryption context is supported only on operations with symmetric
     * encryption KMS keys. On operations with symmetric encryption KMS keys, an encryption context is optional, but it is
     * strongly recommended.
     *
     * For more information, see Encryption context [^2] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#cryptographic-operations
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#encrypt_context
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
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/grant-manage.html#using-grant-token
     *
     * @var string[]|null
     */
    private $grantTokens;

    /**
     * Specifies the KMS key that KMS uses to decrypt the ciphertext.
     *
     * Enter a key ID of the KMS key that was used to encrypt the ciphertext. If you identify a different KMS key, the
     * `Decrypt` operation throws an `IncorrectKeyException`.
     *
     * This parameter is required only when the ciphertext was encrypted under an asymmetric KMS key. If you used a
     * symmetric encryption KMS key, KMS can get the KMS key from metadata that it adds to the symmetric ciphertext blob.
     * However, it is always recommended as a best practice. This practice ensures that you use the KMS key that you intend.
     *
     * To specify a KMS key, use its key ID, key ARN, alias name, or alias ARN. When using an alias name, prefix it with
     * `"alias/"`. To specify a KMS key in a different Amazon Web Services account, you must use the key ARN or alias ARN.
     *
     * For example:
     *
     * - Key ID: `1234abcd-12ab-34cd-56ef-1234567890ab`
     * -
     * - Key ARN: `arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab`
     * -
     * - Alias name: `alias/ExampleAlias`
     * -
     * - Alias ARN: `arn:aws:kms:us-east-2:111122223333:alias/ExampleAlias`
     *
     * To get the key ID and key ARN for a KMS key, use ListKeys or DescribeKey. To get the alias name and alias ARN, use
     * ListAliases.
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Specifies the encryption algorithm that will be used to decrypt the ciphertext. Specify the same algorithm that was
     * used to encrypt the data. If you specify a different algorithm, the `Decrypt` operation fails.
     *
     * This parameter is required only when the ciphertext was encrypted under an asymmetric KMS key. The default value,
     * `SYMMETRIC_DEFAULT`, represents the only supported algorithm that is valid for symmetric encryption KMS keys.
     *
     * @var EncryptionAlgorithmSpec::*|null
     */
    private $encryptionAlgorithm;

    /**
     * A signed attestation document [^1] from an Amazon Web Services Nitro enclave and the encryption algorithm to use with
     * the enclave's public key. The only valid encryption algorithm is `RSAES_OAEP_SHA_256`.
     *
     * This parameter only supports attestation documents for Amazon Web Services Nitro Enclaves. To include this parameter,
     * use the Amazon Web Services Nitro Enclaves SDK [^2] or any Amazon Web Services SDK.
     *
     * When you use this parameter, instead of returning the plaintext data, KMS encrypts the plaintext data with the public
     * key in the attestation document, and returns the resulting ciphertext in the `CiphertextForRecipient` field in the
     * response. This ciphertext can be decrypted only with the private key in the enclave. The `Plaintext` field in the
     * response is null or empty.
     *
     * For information about the interaction between KMS and Amazon Web Services Nitro Enclaves, see How Amazon Web Services
     * Nitro Enclaves uses KMS [^3] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave-how.html#term-attestdoc
     * [^2]: https://docs.aws.amazon.com/enclaves/latest/user/developing-applications.html#sdk
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/services-nitro-enclaves.html
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
