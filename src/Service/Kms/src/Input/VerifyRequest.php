<?php

namespace AsyncAws\Kms\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kms\Enum\MessageType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;

final class VerifyRequest extends Input
{
    /**
     * Identifies the asymmetric KMS key that will be used to verify the signature. This must be the same KMS key that was
     * used to generate the signature. If you specify a different KMS key, the signature verification fails.
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
     * Specifies the message that was signed. You can submit a raw message of up to 4096 bytes, or a hash digest of the
     * message. If you submit a digest, use the `MessageType` parameter with a value of `DIGEST`.
     *
     * If the message specified here is different from the message that was signed, the signature verification fails. A
     * message and its hash digest are considered to be the same message.
     *
     * @required
     *
     * @var string|null
     */
    private $message;

    /**
     * Tells KMS whether the value of the `Message` parameter should be hashed as part of the signing algorithm. Use `RAW`
     * for unhashed messages; use `DIGEST` for message digests, which are already hashed; use `EXTERNAL_MU` for 64-byte
     * representative μ used in ML-DSA signing as defined in NIST FIPS 204 Section 6.2.
     *
     * When the value of `MessageType` is `RAW`, KMS uses the standard signing algorithm, which begins with a hash function.
     * When the value is `DIGEST`, KMS skips the hashing step in the signing algorithm. When the value is `EXTERNAL_MU` KMS
     * skips the concatenated hashing of the public key hash and the message done in the ML-DSA signing algorithm.
     *
     * ! Use the `DIGEST` or `EXTERNAL_MU` value only when the value of the `Message` parameter is a message digest. If you
     * ! use the `DIGEST` value with an unhashed message, the security of the signing operation can be compromised.
     *
     * When using ECC_NIST_EDWARDS25519 KMS keys:
     *
     * - ED25519_SHA_512 signing algorithm requires KMS `MessageType:RAW`
     * - ED25519_PH_SHA_512 signing algorithm requires KMS `MessageType:DIGEST`
     *
     * When the value of `MessageType` is `DIGEST`, the length of the `Message` value must match the length of hashed
     * messages for the specified signing algorithm.
     *
     * When the value of `MessageType` is `EXTERNAL_MU` the length of the `Message` value must be 64 bytes.
     *
     * You can submit a message digest and omit the `MessageType` or specify `RAW` so the digest is hashed again while
     * signing. However, if the signed message is hashed once while signing, but twice while verifying, verification fails,
     * even when the message hasn't changed.
     *
     * The hashing algorithm that `Verify` uses is based on the `SigningAlgorithm` value.
     *
     * - Signing algorithms that end in SHA_256 use the SHA_256 hashing algorithm.
     * - Signing algorithms that end in SHA_384 use the SHA_384 hashing algorithm.
     * - Signing algorithms that end in SHA_512 use the SHA_512 hashing algorithm.
     * - Signing algorithms that end in SHAKE_256 use the SHAKE_256 hashing algorithm.
     * - SM2DSA uses the SM3 hashing algorithm. For details, see Offline verification with SM2 key pairs [^1].
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/offline-operations.html#key-spec-sm-offline-verification
     *
     * @var MessageType::*|null
     */
    private $messageType;

    /**
     * The signature that the `Sign` operation generated.
     *
     * @required
     *
     * @var string|null
     */
    private $signature;

    /**
     * The signing algorithm that was used to sign the message. If you submit a different algorithm, the signature
     * verification fails.
     *
     * @required
     *
     * @var SigningAlgorithmSpec::*|null
     */
    private $signingAlgorithm;

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
     *   MessageType?: MessageType::*|null,
     *   Signature?: string,
     *   SigningAlgorithm?: SigningAlgorithmSpec::*,
     *   GrantTokens?: string[]|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->message = $input['Message'] ?? null;
        $this->messageType = $input['MessageType'] ?? null;
        $this->signature = $input['Signature'] ?? null;
        $this->signingAlgorithm = $input['SigningAlgorithm'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->dryRun = $input['DryRun'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   KeyId?: string,
     *   Message?: string,
     *   MessageType?: MessageType::*|null,
     *   Signature?: string,
     *   SigningAlgorithm?: SigningAlgorithmSpec::*,
     *   GrantTokens?: string[]|null,
     *   DryRun?: bool|null,
     *   '@region'?: string|null,
     * }|VerifyRequest $input
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

    public function getSignature(): ?string
    {
        return $this->signature;
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
            'X-Amz-Target' => 'TrentService.Verify',
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

    public function setSignature(?string $value): self
    {
        $this->signature = $value;

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
        if (null === $v = $this->signature) {
            throw new InvalidArgument(\sprintf('Missing parameter "Signature" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Signature'] = base64_encode($v);
        if (null === $v = $this->signingAlgorithm) {
            throw new InvalidArgument(\sprintf('Missing parameter "SigningAlgorithm" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!SigningAlgorithmSpec::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "SigningAlgorithm" for "%s". The value "%s" is not a valid "SigningAlgorithmSpec".', __CLASS__, $v));
        }
        $payload['SigningAlgorithm'] = $v;
        if (null !== $v = $this->grantTokens) {
            $index = -1;
            $payload['GrantTokens'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GrantTokens'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->dryRun) {
            $payload['DryRun'] = (bool) $v;
        }

        return $payload;
    }
}
