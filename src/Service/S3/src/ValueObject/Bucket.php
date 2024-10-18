<?php

namespace AsyncAws\S3\ValueObject;

/**
 * In terms of implementation, a Bucket is a resource.
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
     * `BucketRegion` indicates the Amazon Web Services region where the bucket is located. If the request contains at least
     * one valid parameter, it is included in the response.
     *
     * @var string|null
     */
    private $bucketRegion;

    /**
     * @param array{
     *   Name?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   BucketRegion?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->bucketRegion = $input['BucketRegion'] ?? null;
    }

    /**
     * @param array{
     *   Name?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   BucketRegion?: null|string,
     * }|Bucket $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucketRegion(): ?string
    {
        return $this->bucketRegion;
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
