<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\ValueObject\RecipientInfo;

final class GenerateDataKeyRequest extends Input
{
    /**
     * Specifies the symmetric encryption KMS key that encrypts the data key. You cannot specify an asymmetric KMS key or a
     * KMS key in a custom key store. To get the type and origin of your KMS key, use the DescribeKey operation.
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
     * Specifies the encryption context that will be used when encrypting the data key.
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
     * For more information, see Encryption context [^1] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/encrypt_context.html
     *
     * @var array<string, string>|null
     */
    private $encryptionContext;

    /**
     * Specifies the length of the data key in bytes. For example, use the value 64 to generate a 512-bit data key (64 bytes
     * is 512 bits). For 128-bit (16-byte) and 256-bit (32-byte) data keys, use the `KeySpec` parameter.
     *
     * You must specify either the `KeySpec` or the `NumberOfBytes` parameter (but not both) in every `GenerateDataKey`
     * request.
     *
     * @var int|null
     */
    private $numberOfBytes;

    /**
     * Specifies the length of the data key. Use `AES_128` to generate a 128-bit symmetric key, or `AES_256` to generate a
     * 256-bit symmetric key.
     *
     * You must specify either the `KeySpec` or the `NumberOfBytes` parameter (but not both) in every `GenerateDataKey`
     * request.
     *
     * @var DataKeySpec::*|null
     */
    private $keySpec;

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
     * A signed attestation document [^1] from an Amazon Web Services Nitro enclave or NitroTPM, and the encryption
     * algorithm to use with the public key in the attestation document. The only valid encryption algorithm is
     * `RSAES_OAEP_SHA_256`.
     *
     * This parameter supports the Amazon Web Services Nitro Enclaves SDK [^2] or any Amazon Web Services SDK for Amazon Web
     * Services Nitro Enclaves. It supports any Amazon Web Services SDK for Amazon Web Services NitroTPM.
     *
     * When you use this parameter, instead of returning the plaintext data key, KMS encrypts the plaintext data key under
     * the public key in the attestation document, and returns the resulting ciphertext in the `CiphertextForRecipient`
     * field in the response. This ciphertext can be decrypted only with the private key in the enclave. The
     * `CiphertextBlob` field in the response contains a copy of the data key encrypted under the KMS key specified by the
     * `KeyId` parameter. The `Plaintext` field in the response is null or empty.
     *
     * For information about the interaction between KMS and Amazon Web Services Nitro Enclaves or Amazon Web Services
     * NitroTPM, see Cryptographic attestation support in KMS [^3] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitro-enclave-how.html#term-attestdoc
     * [^2]: https://docs.aws.amazon.com/enclaves/latest/user/developing-applications.html#sdk
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/cryptographic-attestation.html
     *
     * @var RecipientInfo|null
     */
    private $recipient;

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
     *   EncryptionContext?: null|array<string, string>,
     *   NumberOfBytes?: null|int,
     *   KeySpec?: null|DataKeySpec::*,
     *   GrantTokens?: null|string[],
     *   Recipient?: null|RecipientInfo|array,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->encryptionContext = $input['EncryptionContext'] ?? null;
        $this->numberOfBytes = $input['NumberOfBytes'] ?? null;
        $this->keySpec = $input['KeySpec'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->recipient = isset($input['Recipient']) ? RecipientInfo::create($input['Recipient']) : null;
        $this->dryRun = $input['DryRun'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   KeyId?: string,
     *   EncryptionContext?: null|array<string, string>,
     *   NumberOfBytes?: null|int,
     *   KeySpec?: null|DataKeySpec::*,
     *   GrantTokens?: null|string[],
     *   Recipient?: null|RecipientInfo|array,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * }|GenerateDataKeyRequest $input
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

    /**
     * @return DataKeySpec::*|null
     */
    public function getKeySpec(): ?string
    {
        return $this->keySpec;
    }

    public function getNumberOfBytes(): ?int
    {
        return $this->numberOfBytes;
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
            'X-Amz-Target' => 'TrentService.GenerateDataKey',
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

    /**
     * @param DataKeySpec::*|null $value
     */
    public function setKeySpec(?string $value): self
    {
        $this->keySpec = $value;

        return $this;
    }

    public function setNumberOfBytes(?int $value): self
    {
        $this->numberOfBytes = $value;

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
        if (null === $v = $this->keyId) {
            throw new InvalidArgument(\sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
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
        if (null !== $v = $this->numberOfBytes) {
            $payload['NumberOfBytes'] = $v;
        }
        if (null !== $v = $this->keySpec) {
            if (!DataKeySpec::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "KeySpec" for "%s". The value "%s" is not a valid "DataKeySpec".', __CLASS__, $v));
            }
            $payload['KeySpec'] = $v;
        }
        if (null !== $v = $this->grantTokens) {
            $index = -1;
            $payload['GrantTokens'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GrantTokens'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->recipient) {
            $payload['Recipient'] = $v->requestBody();
        }
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = (bool) $v;
        }

        return $payload;
    }
}
