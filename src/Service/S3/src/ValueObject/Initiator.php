<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Identifies who initiated the multipart upload.
 */
final class Initiator
{
    /**
     * If the principal is an AWS account, it provides the Canonical User ID. If the principal is an IAM User, it provides a
     * user ARN value.
     */
    private $ID;

    /**
     * Name of the Principal.
     */
    private $DisplayName;

    /**
     * @param array{
     *   ID?: null|string,
     *   DisplayName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ID = $input['ID'] ?? null;
        $this->DisplayName = $input['DisplayName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function getID(): ?string
    {
        return $this->ID;
    }
}
