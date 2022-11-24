<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\ServerSideEncryption;

/**
 * Specifies the default server-side encryption to apply to new objects in the bucket. If a PUT Object request doesn't
 * specify any server-side encryption, this default encryption will be applied.
 */
final class ServerSideEncryptionByDefault
{
    /**
     * Server-side encryption algorithm to use for the default encryption.
     */
    private $sseAlgorithm;

    /**
     * Amazon Web Services Key Management Service (KMS) customer Amazon Web Services KMS key ID to use for the default
     * encryption. This parameter is allowed if and only if `SSEAlgorithm` is set to `aws:kms`.
     */
    private $kmsMasterKeyId;

    /**
     * @param array{
     *   SSEAlgorithm: ServerSideEncryption::*,
     *   KMSMasterKeyID?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sseAlgorithm = $input['SSEAlgorithm'] ?? null;
        $this->kmsMasterKeyId = $input['KMSMasterKeyID'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsMasterKeyId(): ?string
    {
        return $this->kmsMasterKeyId;
    }

    /**
     * @return ServerSideEncryption::*
     */
    public function getSseAlgorithm(): string
    {
        return $this->sseAlgorithm;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null === $v = $this->sseAlgorithm) {
            throw new InvalidArgument(sprintf('Missing parameter "SSEAlgorithm" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ServerSideEncryption::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "SSEAlgorithm" for "%s". The value "%s" is not a valid "ServerSideEncryption".', __CLASS__, $v));
        }
        $node->appendChild($document->createElement('SSEAlgorithm', $v));
        if (null !== $v = $this->kmsMasterKeyId) {
            $node->appendChild($document->createElement('KMSMasterKeyID', $v));
        }
    }
}
