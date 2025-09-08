<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides the input image either as bytes or an S3 object.
 *
 * You pass image bytes to an Amazon Rekognition API operation by using the `Bytes` property. For example, you would use
 * the `Bytes` property to pass an image loaded from a local file system. Image bytes passed by using the `Bytes`
 * property must be base64-encoded. Your code may not need to encode image bytes if you are using an AWS SDK to call
 * Amazon Rekognition API operations.
 *
 * For more information, see Analyzing an Image Loaded from a Local File System in the Amazon Rekognition Developer
 * Guide.
 *
 * You pass images stored in an S3 bucket to an Amazon Rekognition API operation by using the `S3Object` property.
 * Images stored in an S3 bucket do not need to be base64-encoded.
 *
 * The region for the S3 bucket containing the S3 object must match the region you use for Amazon Rekognition
 * operations.
 *
 * If you use the AWS CLI to call Amazon Rekognition operations, passing image bytes using the Bytes property is not
 * supported. You must first upload the image to an Amazon S3 bucket and then call the operation using the S3Object
 * property.
 *
 * For Amazon Rekognition to process an S3 object, the user must have permission to access the S3 object. For more
 * information, see How Amazon Rekognition works with IAM in the Amazon Rekognition Developer Guide.
 */
final class Image
{
    /**
     * Blob of image bytes up to 5 MBs. Note that the maximum image size you can pass to `DetectCustomLabels` is 4MB.
     *
     * @var string|null
     */
    private $bytes;

    /**
     * Identifies an S3 object as the image source.
     *
     * @var S3Object|null
     */
    private $s3Object;

    /**
     * @param array{
     *   Bytes?: string|null,
     *   S3Object?: S3Object|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bytes = $input['Bytes'] ?? null;
        $this->s3Object = isset($input['S3Object']) ? S3Object::create($input['S3Object']) : null;
    }

    /**
     * @param array{
     *   Bytes?: string|null,
     *   S3Object?: S3Object|array|null,
     * }|Image $input
     */
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
