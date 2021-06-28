<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * (Optional) Custom type consisting of a `Region` (required) and the `KmsKeyId` which can be an `ARN`, `Key ID`, or
 * `Alias`.
 */
final class ReplicaRegionType
{
    /**
     * Describes a single instance of Region objects.
     */
    private $region;

    /**
     * Can be an `ARN`, `Key ID`, or `Alias`.
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
