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
     * The Base64 encoded, 32-bit `CRC32` checksum of the part. This checksum is present if the object was uploaded with the
     * `CRC32` checksum algorithm. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * The Base64 encoded, 32-bit `CRC32C` checksum of the part. This checksum is present if the object was uploaded with
     * the `CRC32C` checksum algorithm. For more information, see Checking object integrity [^1] in the *Amazon S3 User
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * The Base64 encoded, 64-bit `CRC64NVME` checksum of the part. This checksum is present if the multipart upload request
     * was created with the `CRC64NVME` checksum algorithm, or if the object was uploaded without a checksum (and Amazon S3
     * added the default checksum, `CRC64NVME`, to the uploaded object). For more information, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc64Nvme;

    /**
     * The Base64 encoded, 160-bit `SHA1` checksum of the part. This checksum is present if the object was uploaded with the
     * `SHA1` checksum algorithm. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * The Base64 encoded, 256-bit `SHA256` checksum of the part. This checksum is present if the object was uploaded with
     * the `SHA256` checksum algorithm. For more information, see Checking object integrity [^1] in the *Amazon S3 User
     * Guide*.
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
     *   ChecksumCRC64NVME?: null|string,
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
        $this->checksumCrc64Nvme = $input['ChecksumCRC64NVME'] ?? null;
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
     *   ChecksumCRC64NVME?: null|string,
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

    public function getChecksumCrc64Nvme(): ?string
    {
        return $this->checksumCrc64Nvme;
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
