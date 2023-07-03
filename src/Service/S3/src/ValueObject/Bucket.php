<?php

namespace AsyncAws\S3\ValueObject;

/**
 * In terms of implementation, a Bucket is a resource. An Amazon S3 bucket name is globally unique, and the namespace is
 * shared by all Amazon Web Services accounts.
 */
final class Bucket
{
    /**
     * The name of the bucket.
     *
     * @var string|null
     */
    private $name;

    /**
     * Date the bucket was created. This date can change when making changes to your bucket, such as editing its bucket
     * policy.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * @param array{
     *   Name?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
    }

    /**
     * @param array{
     *   Name?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     * }|Bucket $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
