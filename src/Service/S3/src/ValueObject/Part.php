<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Container for elements related to a part.
 */
final class Part
{
    /**
     * Part number identifying the part. This is a positive integer between 1 and 10,000.
     *
     * @var int|null
     */
    private $partNumber;

    /**
     * Date and time at which the part was uploaded.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModified;

    /**
     * Entity tag returned when the part was uploaded.
     *
     * @var string|null
     */
    private $etag;

    /**
     * Size in bytes of the uploaded part data.
     *
     * @var int|null
     */
    private $size;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 32-bit CRC-32 checksum of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * The base64-encoded, 32-bit CRC-32C checksum of the object. This will only be present if it was uploaded with the
     * object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not be a
     * direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual
     * part. For more information about how checksums are calculated with multipart uploads, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * The base64-encoded, 160-bit SHA-1 digest of the object. This will only be present if it was uploaded with the object.
     * When you use the API operation on an object that was uploaded using multipart uploads, this value may not be a direct
     * checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual part.
     * For more information about how checksums are calculated with multipart uploads, see Checking object integrity [^1] in
     * the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 256-bit SHA-256 digest of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * @param array{
     *   PartNumber?: null|int,
     *   LastModified?: null|\DateTimeImmutable,
     *   ETag?: null|string,
     *   Size?: null|int,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->partNumber = $input['PartNumber'] ?? null;
        $this->lastModified = $input['LastModified'] ?? null;
        $this->etag = $input['ETag'] ?? null;
        $this->size = $input['Size'] ?? null;
        $this->checksumCrc32 = $input['ChecksumCRC32'] ?? null;
        $this->checksumCrc32C = $input['ChecksumCRC32C'] ?? null;
        $this->checksumSha1 = $input['ChecksumSHA1'] ?? null;
        $this->checksumSha256 = $input['ChecksumSHA256'] ?? null;
    }

    /**
     * @param array{
     *   PartNumber?: null|int,
     *   LastModified?: null|\DateTimeImmutable,
     *   ETag?: null|string,
     *   Size?: null|int,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     * }|Part $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getChecksumCrc32(): ?string
    {
        return $this->checksumCrc32;
    }

    public function getChecksumCrc32C(): ?string
    {
        return $this->checksumCrc32C;
    }

    public function getChecksumSha1(): ?string
    {
        return $this->checksumSha1;
    }

    public function getChecksumSha256(): ?string
    {
        return $this->checksumSha256;
    }

    public function getEtag(): ?string
    {
        return $this->etag;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        return $this->lastModified;
    }

    public function getPartNumber(): ?int
    {
        return $this->partNumber;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}
