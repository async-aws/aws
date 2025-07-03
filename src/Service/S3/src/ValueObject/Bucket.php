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
     * The Amazon Resource Name (ARN) of the S3 bucket. ARNs uniquely identify Amazon Web Services resources across all of
     * Amazon Web Services.
     *
     * > This parameter is only supported for S3 directory buckets. For more information, see Using tags with directory
     * > buckets [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-tagging.html
     *
     * @var string|null
     */
    private $bucketArn;

    /**
     * @param array{
     *   Name?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   BucketRegion?: null|string,
     *   BucketArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->bucketRegion = $input['BucketRegion'] ?? null;
        $this->bucketArn = $input['BucketArn'] ?? null;
    }

    /**
     * @param array{
     *   Name?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   BucketRegion?: null|string,
     *   BucketArn?: null|string,
     * }|Bucket $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucketArn(): ?string
    {
        return $this->bucketArn;
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
