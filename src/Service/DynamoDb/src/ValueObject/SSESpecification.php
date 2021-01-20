<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\SSEType;

/**
 * Represents the settings used to enable server-side encryption.
 */
final class SSESpecification
{
    /**
     * Indicates whether server-side encryption is done using an AWS managed CMK or an AWS owned CMK. If enabled (true),
     * server-side encryption type is set to `KMS` and an AWS managed CMK is used (AWS KMS charges apply). If disabled
     * (false) or not specified, server-side encryption is set to AWS owned CMK.
     */
    private $enabled;

    /**
     * Server-side encryption type. The only supported value is:.
     */
    private $sSEType;

    /**
     * The AWS KMS customer master key (CMK) that should be used for the AWS KMS encryption. To specify a CMK, use its key
     * ID, Amazon Resource Name (ARN), alias name, or alias ARN. Note that you should only provide this parameter if the key
     * is different from the default DynamoDB customer master key alias/aws/dynamodb.
     */
    private $kMSMasterKeyId;

    /**
     * @param array{
     *   Enabled?: null|bool,
     *   SSEType?: null|SSEType::*,
     *   KMSMasterKeyId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? null;
        $this->sSEType = $input['SSEType'] ?? null;
        $this->kMSMasterKeyId = $input['KMSMasterKeyId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function getKMSMasterKeyId(): ?string
    {
        return $this->kMSMasterKeyId;
    }

    /**
     * @return SSEType::*|null
     */
    public function getSSEType(): ?string
    {
        return $this->sSEType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->enabled) {
            $payload['Enabled'] = (bool) $v;
        }
        if (null !== $v = $this->sSEType) {
            if (!SSEType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "SSEType" for "%s". The value "%s" is not a valid "SSEType".', __CLASS__, $v));
            }
            $payload['SSEType'] = $v;
        }
        if (null !== $v = $this->kMSMasterKeyId) {
            $payload['KMSMasterKeyId'] = $v;
        }

        return $payload;
    }
}
