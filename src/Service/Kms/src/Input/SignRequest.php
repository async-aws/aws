<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\MessageType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

final class SignRequest extends Input
{
    /**
     * Identifies an asymmetric KMS key. KMS uses the private key in the asymmetric KMS key to sign the message. The
     * `KeyUsage` type of the KMS key must be `SIGN_VERIFY`. To find the `KeyUsage` of a KMS key, use the DescribeKey
     * operation.
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
     * Specifies the message or message digest to sign. Messages can be 0-4096 bytes. To sign a larger message, provide a
     * message digest.
     *
     * If you provide a message digest, use the `DIGEST` value of `MessageType` to prevent the digest from being hashed
     * again while signing.
     *
     * @required
     *
     * @var string|null
     */
    private $message;

    /**
     * Tells KMS whether the value of the `Message` parameter should be hashed as part of the signing algorithm. Use `RAW`
     * for unhashed messages; use `DIGEST` for message digests, which are already hashed.
     *
     * When the value of `MessageType` is `RAW`, KMS uses the standard signing algorithm, which begins with a hash function.
     * When the value is `DIGEST`, KMS skips the hashing step in the signing algorithm.
     *
     * ! Use the `DIGEST` value only when the value of the `Message` parameter is a message digest. If you use the `DIGEST`
     * ! value with an unhashed message, the security of the signing operation can be compromised.
     *
     * When the value of `MessageType`is `DIGEST`, the length of the `Message` value must match the length of hashed
     * messages for the specified signing algorithm.
     *
     * You can submit a message digest and omit the `MessageType` or specify `RAW` so the digest is hashed again while
     * signing. However, this can cause verification failures when verifying with a system that assumes a single hash.
     *
     * The hashing algorithm in that `Sign` uses is based on the `SigningAlgorithm` value.
     *
     * - Signing algorithms that end in SHA_256 use the SHA_256 hashing algorithm.
     * - Signing algorithms that end in SHA_384 use the SHA_384 hashing algorithm.
     * - Signing algorithms that end in SHA_512 use the SHA_512 hashing algorithm.
     * - SM2DSA uses the SM3 hashing algorithm. For details, see Offline verification with SM2 key pairs [^1].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/offline-operations.html#key-spec-sm-offline-verification
     *
     * @var MessageType::*|null
     */
    private $messageType;

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
     * Specifies the signing algorithm to use when signing the message.
     *
     * Choose an algorithm that is compatible with the type and size of the specified asymmetric KMS key. When signing with
     * RSA key pairs, RSASSA-PSS algorithms are preferred. We include RSASSA-PKCS1-v1_5 algorithms for compatibility with
     * existing applications.
     *
     * @required
     *
     * @var SigningAlgorithmSpec::*|null
     */
    private $signingAlgorithm;

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
     *   Message?: string,
     *   MessageType?: null|MessageType::*,
     *   GrantTokens?: null|string[],
     *   SigningAlgorithm?: SigningAlgorithmSpec::*,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->message = $input['Message'] ?? null;
        $this->messageType = $input['MessageType'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->signingAlgorithm = $input['SigningAlgorithm'] ?? null;
        $this->dryRun = $input['DryRun'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   KeyId?: string,
     *   Message?: string,
     *   MessageType?: null|MessageType::*,
     *   GrantTokens?: null|string[],
     *   SigningAlgorithm?: SigningAlgorithmSpec::*,
     *   DryRun?: null|bool,
     *   '@region'?: string|null,
     * }|SignRequest $input
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return MessageType::*|null
     */
    public function getMessageType(): ?string
    {
        return $this->messageType;
    }

    /**
     * @return SigningAlgorithmSpec::*|null
     */
    public function getSigningAlgorithm(): ?string
    {
        return $this->signingAlgorithm;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'TrentService.Sign',
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

    public function setMessage(?string $value): self
    {
        $this->message = $value;

        return $this;
    }

    /**
     * @param MessageType::*|null $value
     */
    public function setMessageType(?string $value): self
    {
        $this->messageType = $value;

        return $this;
    }

    /**
     * @param SigningAlgorithmSpec::*|null $value
     */
    public function setSigningAlgorithm(?string $value): self
    {
        $this->signingAlgorithm = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->keyId) {
            throw new InvalidArgument(\sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
        if (null === $v = $this->message) {
            throw new InvalidArgument(\sprintf('Missing parameter "Message" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Message'] = base64_encode($v);
        if (null !== $v = $this->messageType) {
            if (!MessageType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "MessageType" for "%s". The value "%s" is not a valid "MessageType".', __CLASS__, $v));
            }
            $payload['MessageType'] = $v;
        }
        if (null !== $v = $this->grantTokens) {
            $index = -1;
            $payload['GrantTokens'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GrantTokens'][$index] = $listValue;
            }
        }
        if (null === $v = $this->signingAlgorithm) {
            throw new InvalidArgument(\sprintf('Missing parameter "SigningAlgorithm" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!SigningAlgorithmSpec::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "SigningAlgorithm" for "%s". The value "%s" is not a valid "SigningAlgorithmSpec".', __CLASS__, $v));
        }
        $payload['SigningAlgorithm'] = $v;
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = (bool) $v;
        }

        return $payload;
    }
}
