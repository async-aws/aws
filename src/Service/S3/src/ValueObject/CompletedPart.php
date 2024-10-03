<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Details of the parts that were uploaded.
 */
final class CompletedPart
{
    /**
     * Entity tag returned when the part was uploaded.
     *
     * @var string|null
     */
    private $etag;

    /**
     * The base64-encoded, 32-bit CRC-32 checksum of the object. This will only be present if it was uploaded with the
     * object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not be a
     * direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual
     * part. For more information about how checksums are calculated with multipart uploads, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
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
     * The base64-encoded, 256-bit SHA-256 digest of the object. This will only be present if it was uploaded with the
     * object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not be a
     * direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual
     * part. For more information about how checksums are calculated with multipart uploads, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * Part number that identifies the part. This is a positive integer between 1 and 10,000.
     *
     * > - **General purpose buckets** - In `CompleteMultipartUpload`, when a additional checksum (including
     * >   `x-amz-checksum-crc32`, `x-amz-checksum-crc32c`, `x-amz-checksum-sha1`, or `x-amz-checksum-sha256`) is applied to
     * >   each part, the `PartNumber` must start at 1 and the part numbers must be consecutive. Otherwise, Amazon S3
     * >   generates an HTTP `400 Bad Request` status code and an `InvalidPartOrder` error code.
     * > - **Directory buckets** - In `CompleteMultipartUpload`, the `PartNumber` must start at 1 and the part numbers must
     * >   be consecutive.
     * >
     *
     * @var int|null
     */
    private $partNumber;

    /**
     * @param array{
     *   ETag?: null|string,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   PartNumber?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->etag = $input['ETag'] ?? null;
        $this->checksumCrc32 = $input['ChecksumCRC32'] ?? null;
        $this->checksumCrc32C = $input['ChecksumCRC32C'] ?? null;
        $this->checksumSha1 = $input['ChecksumSHA1'] ?? null;
        $this->checksumSha256 = $input['ChecksumSHA256'] ?? null;
        $this->partNumber = $input['PartNumber'] ?? null;
    }

    /**
     * @param array{
     *   ETag?: null|string,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   PartNumber?: null|int,
     * }|CompletedPart $input
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

    public function getPartNumber(): ?int
    {
        return $this->partNumber;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->etag) {
            $node->appendChild($document->createElement('ETag', $v));
        }
        if (null !== $v = $this->checksumCrc32) {
            $node->appendChild($document->createElement('ChecksumCRC32', $v));
        }
        if (null !== $v = $this->checksumCrc32C) {
            $node->appendChild($document->createElement('ChecksumCRC32C', $v));
        }
        if (null !== $v = $this->checksumSha1) {
            $node->appendChild($document->createElement('ChecksumSHA1', $v));
        }
        if (null !== $v = $this->checksumSha256) {
            $node->appendChild($document->createElement('ChecksumSHA256', $v));
        }
        if (null !== $v = $this->partNumber) {
            $node->appendChild($document->createElement('PartNumber', (string) $v));
        }
    }
}
