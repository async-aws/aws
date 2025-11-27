<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\Enum\EncryptionType;

final class StopStreamEncryptionInput extends Input
{
    /**
     * The name of the stream on which to stop encrypting records.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The encryption type. The only valid value is `KMS`.
     *
     * @required
     *
     * @var EncryptionType::*|null
     */
    private $encryptionType;

    /**
     * The GUID for the customer-managed Amazon Web Services KMS key to use for encryption. This value can be a globally
     * unique identifier, a fully specified Amazon Resource Name (ARN) to either an alias or a key, or an alias name
     * prefixed by "alias/".You can also use a master key owned by Kinesis Data Streams by specifying the alias
     * `aws/kinesis`.
     *
     * - Key ARN example: `arn:aws:kms:us-east-1:123456789012:key/12345678-1234-1234-1234-123456789012`
     * - Alias ARN example: `arn:aws:kms:us-east-1:123456789012:alias/MyAliasName`
     * - Globally unique key ID example: `12345678-1234-1234-1234-123456789012`
     * - Alias name example: `alias/MyAliasName`
     * - Master key owned by Kinesis Data Streams: `alias/aws/kinesis`
     *
     * @required
     *
     * @var string|null
     */
    private $keyId;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string|null,
     *   EncryptionType?: EncryptionType::*,
     *   KeyId?: string,
     *   StreamARN?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->encryptionType = $input['EncryptionType'] ?? null;
        $this->keyId = $input['KeyId'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: string|null,
     *   EncryptionType?: EncryptionType::*,
     *   KeyId?: string,
     *   StreamARN?: string|null,
     *   '@region'?: string|null,
     * }|StopStreamEncryptionInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EncryptionType::*|null
     */
    public function getEncryptionType(): ?string
    {
        return $this->encryptionType;
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.StopStreamEncryption',
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

    /**
     * @param EncryptionType::*|null $value
     */
    public function setEncryptionType(?string $value): self
    {
        $this->encryptionType = $value;

        return $this;
    }

    public function setKeyId(?string $value): self
    {
        $this->keyId = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

        return $this;
    }

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null === $v = $this->encryptionType) {
            throw new InvalidArgument(\sprintf('Missing parameter "EncryptionType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!EncryptionType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "EncryptionType" for "%s". The value "%s" is not a valid "EncryptionType".', __CLASS__, $v));
        }
        $payload['EncryptionType'] = $v;
        if (null === $v = $this->keyId) {
            throw new InvalidArgument(\sprintf('Missing parameter "KeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['KeyId'] = $v;
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
