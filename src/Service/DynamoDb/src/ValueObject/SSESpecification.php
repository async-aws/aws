<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\SSEType;

final class SSESpecification
{
    /**
     * Indicates whether server-side encryption is done using an AWS managed CMK or an AWS owned CMK. If enabled (true),
     * server-side encryption type is set to `KMS` and an AWS managed CMK is used (AWS KMS charges apply). If disabled
     * (false) or not specified, server-side encryption is set to AWS owned CMK.
     */
    private $Enabled;

    /**
     * Server-side encryption type. The only supported value is:.
     */
    private $SSEType;

    /**
     * The AWS KMS customer master key (CMK) that should be used for the AWS KMS encryption. To specify a CMK, use its key
     * ID, Amazon Resource Name (ARN), alias name, or alias ARN. Note that you should only provide this parameter if the key
     * is different from the default DynamoDB customer master key alias/aws/dynamodb.
     */
    private $KMSMasterKeyId;

    /**
     * @param array{
     *   Enabled?: null|bool,
     *   SSEType?: null|\AsyncAws\DynamoDb\Enum\SSEType::*,
     *   KMSMasterKeyId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Enabled = $input['Enabled'] ?? null;
        $this->SSEType = $input['SSEType'] ?? null;
        $this->KMSMasterKeyId = $input['KMSMasterKeyId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): ?bool
    {
        return $this->Enabled;
    }

    public function getKMSMasterKeyId(): ?string
    {
        return $this->KMSMasterKeyId;
    }

    /**
     * @return SSEType::*|null
     */
    public function getSSEType(): ?string
    {
        return $this->SSEType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Enabled) {
            $payload['Enabled'] = (bool) $v;
        }
        if (null !== $v = $this->SSEType) {
            if (!SSEType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "SSEType" for "%s". The value "%s" is not a valid "SSEType".', __CLASS__, $v));
            }
            $payload['SSEType'] = $v;
        }
        if (null !== $v = $this->KMSMasterKeyId) {
            $payload['KMSMasterKeyId'] = $v;
        }

        return $payload;
    }
}
