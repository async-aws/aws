<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Specifies the days since the initiation of an incomplete multipart upload that Amazon S3 will wait before permanently
 * removing all parts of the upload. For more information, see Aborting Incomplete Multipart Uploads Using a Bucket
 * Lifecycle Configuration [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html#mpu-abort-incomplete-mpu-lifecycle-config
 */
final class AbortIncompleteMultipartUpload
{
    /**
     * Specifies the number of days after which Amazon S3 aborts an incomplete multipart upload.
     *
     * @var int|null
     */
    private $daysAfterInitiation;

    /**
     * @param array{
     *   DaysAfterInitiation?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->daysAfterInitiation = $input['DaysAfterInitiation'] ?? null;
    }

    /**
     * @param array{
     *   DaysAfterInitiation?: int|null,
     * }|AbortIncompleteMultipartUpload $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDaysAfterInitiation(): ?int
    {
        return $this->daysAfterInitiation;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->daysAfterInitiation) {
            $node->appendChild($document->createElement('DaysAfterInitiation', (string) $v));
        }
    }
}
