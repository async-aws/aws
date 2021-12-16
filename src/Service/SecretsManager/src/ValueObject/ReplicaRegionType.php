<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A custom type that specifies a `Region` and the `KmsKeyId` for a replica secret.
 */
final class ReplicaRegionType
{
    /**
     * A Region code. For a list of Region codes, see Name and code of Regions.
     *
     * @see https://docs.aws.amazon.com/general/latest/gr/rande.html#regional-endpoints
     */
    private $region;

    /**
     * The ARN, key ID, or alias of the KMS key to encrypt the secret. If you don't include this field, Secrets Manager uses
     * `aws/secretsmanager`.
     */
    private $kmsKeyId;

    /**
     * @param array{
     *   Region?: null|string,
     *   KmsKeyId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->region = $input['Region'] ?? null;
        $this->kmsKeyId = $input['KmsKeyId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->region) {
            $payload['Region'] = $v;
        }
        if (null !== $v = $this->kmsKeyId) {
            $payload['KmsKeyId'] = $v;
        }

        return $payload;
    }
}
