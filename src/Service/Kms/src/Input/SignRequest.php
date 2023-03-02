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
     * @required
     *
     * @var string|null
     */
    private $keyId;

    /**
     * Specifies the message or message digest to sign. Messages can be 0-4096 bytes. To sign a larger message, provide a
     * message digest.
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
     * @var MessageType::*|null
     */
    private $messageType;

    /**
     * A list of grant tokens.
     *
     * @var string[]|null
     */
    private $grantTokens;

    /**
     * Specifies the signing algorithm to use when signing the message.
     *
     * @required
     *
     * @var SigningAlgorithmSpec::*|null
     */
    private $signingAlgorithm;

    /**
     * @param array{
     *   KeyId?: string,
     *   Message?: string,
     *   MessageType?: MessageType::*,
     *   GrantTokens?: string[],
     *   SigningAlgorithm?: SigningAlgorithmSpec::*,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->keyId = $input['KeyId'] ?? null;
        $this->message = $input['Message'] ?? null;
        $this->messageType = $input['MessageType'] ?? null;
        $this->grantTokens = $input['GrantTokens'] ?? null;
        $this->signingAlgorithm = $input['SigningAlgorithm'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
            throw new InvalidArgument(sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
        if (null === $v = $this->message) {
            throw new InvalidArgument(sprintf('Missing parameter "Message" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Message'] = base64_encode($v);
        if (null !== $v = $this->messageType) {
            if (!MessageType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "MessageType" for "%s". The value "%s" is not a valid "MessageType".', __CLASS__, $v));
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
            throw new InvalidArgument(sprintf('Missing parameter "SigningAlgorithm" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!SigningAlgorithmSpec::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "SigningAlgorithm" for "%s". The value "%s" is not a valid "SigningAlgorithmSpec".', __CLASS__, $v));
        }
        $payload['SigningAlgorithm'] = $v;

        return $payload;
    }
}
