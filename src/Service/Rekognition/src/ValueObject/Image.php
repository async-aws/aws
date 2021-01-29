<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * The input image as base64-encoded bytes or an S3 object. If you use the AWS CLI to call Amazon Rekognition
 * operations, passing base64-encoded image bytes is not supported.
 * If you are using an AWS SDK to call Amazon Rekognition, you might not need to base64-encode image bytes passed using
 * the `Bytes` field. For more information, see Images in the Amazon Rekognition developer guide.
 */
final class Image
{
    /**
     * Blob of image bytes up to 5 MBs.
     */
    private $bytes;

    /**
     * Identifies an S3 object as the image source.
     */
    private $s3Object;

    /**
     * @param array{
     *   Bytes?: null|string,
     *   S3Object?: null|S3Object|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bytes = $input['Bytes'] ?? null;
        $this->s3Object = isset($input['S3Object']) ? S3Object::create($input['S3Object']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBytes(): ?string
    {
        return $this->bytes;
    }

    public function getS3Object(): ?S3Object
    {
        return $this->s3Object;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bytes) {
            $payload['Bytes'] = base64_encode($v);
        }
        if (null !== $v = $this->s3Object) {
            $payload['S3Object'] = $v->requestBody();
        }

        return $payload;
    }
}
