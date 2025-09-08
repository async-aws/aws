<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\Enum\Attribute;
use AsyncAws\Rekognition\ValueObject\Image;

final class DetectFacesRequest extends Input
{
    /**
     * The input image as base64-encoded bytes or an S3 object. If you use the AWS CLI to call Amazon Rekognition
     * operations, passing base64-encoded image bytes is not supported.
     *
     * If you are using an AWS SDK to call Amazon Rekognition, you might not need to base64-encode image bytes passed using
     * the `Bytes` field. For more information, see Images in the Amazon Rekognition developer guide.
     *
     * @required
     *
     * @var Image|null
     */
    private $image;

    /**
     * An array of facial attributes you want to be returned. A `DEFAULT` subset of facial attributes - `BoundingBox`,
     * `Confidence`, `Pose`, `Quality`, and `Landmarks` - will always be returned. You can request for specific facial
     * attributes (in addition to the default list) - by using [`"DEFAULT", "FACE_OCCLUDED"`] or just [`"FACE_OCCLUDED"`].
     * You can request for all facial attributes by using [`"ALL"]`. Requesting more attributes may increase response time.
     *
     * If you provide both, `["ALL", "DEFAULT"]`, the service uses a logical "AND" operator to determine which attributes to
     * return (in this case, all attributes).
     *
     * Note that while the FaceOccluded and EyeDirection attributes are supported when using `DetectFaces`, they aren't
     * supported when analyzing videos with `StartFaceDetection` and `GetFaceDetection`.
     *
     * @var list<Attribute::*>|null
     */
    private $attributes;

    /**
     * @param array{
     *   Image?: Image|array,
     *   Attributes?: array<Attribute::*>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->image = isset($input['Image']) ? Image::create($input['Image']) : null;
        $this->attributes = $input['Attributes'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Image?: Image|array,
     *   Attributes?: array<Attribute::*>|null,
     *   '@region'?: string|null,
     * }|DetectFacesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Attribute::*>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.DetectFaces',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param list<Attribute::*> $value
     */
    public function setAttributes(array $value): self
    {
        $this->attributes = $value;

        return $this;
    }

    public function setImage(?Image $value): self
    {
        $this->image = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->image) {
            throw new InvalidArgument(\sprintf('Missing parameter "Image" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Image'] = $v->requestBody();
        if (null !== $v = $this->attributes) {
            $index = -1;
            $payload['Attributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!Attribute::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "Attributes" for "%s". The value "%s" is not a valid "Attribute".', __CLASS__, $listValue));
                }
                $payload['Attributes'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
