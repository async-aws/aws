<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Container element that identifies who initiated the multipart upload.
 */
final class Initiator
{
    /**
     * If the principal is an Amazon Web Services account, it provides the Canonical User ID. If the principal is an IAM
     * User, it provides a user ARN value.
     *
     * > **Directory buckets** - If the principal is an Amazon Web Services account, it provides the Amazon Web Services
     * > account ID. If the principal is an IAM User, it provides a user ARN value.
     *
     * @var string|null
     */
    private $id;

    /**
     * Name of the Principal.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $displayName;

    /**
     * @param array{
     *   ID?: null|string,
     *   DisplayName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['ID'] ?? null;
        $this->displayName = $input['DisplayName'] ?? null;
    }

    /**
     * @param array{
     *   ID?: null|string,
     *   DisplayName?: null|string,
     * }|Initiator $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
