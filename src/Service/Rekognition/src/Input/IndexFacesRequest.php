<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\Enum\Attribute;
use AsyncAws\Rekognition\Enum\QualityFilter;
use AsyncAws\Rekognition\ValueObject\Image;

final class IndexFacesRequest extends Input
{
    /**
     * The ID of an existing collection to which you want to add the faces that are detected in the input images.
     *
     * @required
     *
     * @var string|null
     */
    private $collectionId;

    /**
     * The input image as base64-encoded bytes or an S3 object. If you use the AWS CLI to call Amazon Rekognition
     * operations, passing base64-encoded image bytes isn't supported.
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
     * The ID you want to assign to all the faces detected in the image.
     *
     * @var string|null
     */
    private $externalImageId;

    /**
     * An array of facial attributes you want to be returned. A `DEFAULT` subset of facial attributes - `BoundingBox`,
     * `Confidence`, `Pose`, `Quality`, and `Landmarks` - will always be returned. You can request for specific facial
     * attributes (in addition to the default list) - by using `["DEFAULT", "FACE_OCCLUDED"]` or just `["FACE_OCCLUDED"]`.
     * You can request for all facial attributes by using `["ALL"]`. Requesting more attributes may increase response time.
     *
     * If you provide both, `["ALL", "DEFAULT"]`, the service uses a logical AND operator to determine which attributes to
     * return (in this case, all attributes).
     *
     * @var list<Attribute::*>|null
     */
    private $detectionAttributes;

    /**
     * The maximum number of faces to index. The value of `MaxFaces` must be greater than or equal to 1. `IndexFaces`
     * returns no more than 100 detected faces in an image, even if you specify a larger value for `MaxFaces`.
     *
     * If `IndexFaces` detects more faces than the value of `MaxFaces`, the faces with the lowest quality are filtered out
     * first. If there are still more faces than the value of `MaxFaces`, the faces with the smallest bounding boxes are
     * filtered out (up to the number that's needed to satisfy the value of `MaxFaces`). Information about the unindexed
     * faces is available in the `UnindexedFaces` array.
     *
     * The faces that are returned by `IndexFaces` are sorted by the largest face bounding box size to the smallest size, in
     * descending order.
     *
     * `MaxFaces` can be used with a collection associated with any version of the face model.
     *
     * @var int|null
     */
    private $maxFaces;

    /**
     * A filter that specifies a quality bar for how much filtering is done to identify faces. Filtered faces aren't
     * indexed. If you specify `AUTO`, Amazon Rekognition chooses the quality bar. If you specify `LOW`, `MEDIUM`, or
     * `HIGH`, filtering removes all faces that don’t meet the chosen quality bar. The default value is `AUTO`. The
     * quality bar is based on a variety of common use cases. Low-quality detections can occur for a number of reasons. Some
     * examples are an object that's misidentified as a face, a face that's too blurry, or a face with a pose that's too
     * extreme to use. If you specify `NONE`, no filtering is performed.
     *
     * To use quality filtering, the collection you are using must be associated with version 3 of the face model or higher.
     *
     * @var QualityFilter::*|null
     */
    private $qualityFilter;

    /**
     * @param array{
     *   CollectionId?: string,
     *   Image?: Image|array,
     *   ExternalImageId?: string|null,
     *   DetectionAttributes?: array<Attribute::*>|null,
     *   MaxFaces?: int|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->collectionId = $input['CollectionId'] ?? null;
        $this->image = isset($input['Image']) ? Image::create($input['Image']) : null;
        $this->externalImageId = $input['ExternalImageId'] ?? null;
        $this->detectionAttributes = $input['DetectionAttributes'] ?? null;
        $this->maxFaces = $input['MaxFaces'] ?? null;
        $this->qualityFilter = $input['QualityFilter'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CollectionId?: string,
     *   Image?: Image|array,
     *   ExternalImageId?: string|null,
     *   DetectionAttributes?: array<Attribute::*>|null,
     *   MaxFaces?: int|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * }|IndexFacesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionId(): ?string
    {
        return $this->collectionId;
    }

    /**
     * @return list<Attribute::*>
     */
    public function getDetectionAttributes(): array
    {
        return $this->detectionAttributes ?? [];
    }

    public function getExternalImageId(): ?string
    {
        return $this->externalImageId;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function getMaxFaces(): ?int
    {
        return $this->maxFaces;
    }

    /**
     * @return QualityFilter::*|null
     */
    public function getQualityFilter(): ?string
    {
        return $this->qualityFilter;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.IndexFaces',
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

    public function setCollectionId(?string $value): self
    {
        $this->collectionId = $value;

        return $this;
    }

    /**
     * @param list<Attribute::*> $value
     */
    public function setDetectionAttributes(array $value): self
    {
        $this->detectionAttributes = $value;

        return $this;
    }

    public function setExternalImageId(?string $value): self
    {
        $this->externalImageId = $value;

        return $this;
    }

    public function setImage(?Image $value): self
    {
        $this->image = $value;

        return $this;
    }

    public function setMaxFaces(?int $value): self
    {
        $this->maxFaces = $value;

        return $this;
    }

    /**
     * @param QualityFilter::*|null $value
     */
    public function setQualityFilter(?string $value): self
    {
        $this->qualityFilter = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->collectionId) {
            throw new InvalidArgument(\sprintf('Missing parameter "CollectionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CollectionId'] = $v;
        if (null === $v = $this->image) {
            throw new InvalidArgument(\sprintf('Missing parameter "Image" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Image'] = $v->requestBody();
        if (null !== $v = $this->externalImageId) {
            $payload['ExternalImageId'] = $v;
        }
        if (null !== $v = $this->detectionAttributes) {
            $index = -1;
            $payload['DetectionAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!Attribute::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "DetectionAttributes" for "%s". The value "%s" is not a valid "Attribute".', __CLASS__, $listValue));
                }
                $payload['DetectionAttributes'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->maxFaces) {
            $payload['MaxFaces'] = $v;
        }
        if (null !== $v = $this->qualityFilter) {
            if (!QualityFilter::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "QualityFilter" for "%s". The value "%s" is not a valid "QualityFilter".', __CLASS__, $v));
            }
            $payload['QualityFilter'] = $v;
        }

        return $payload;
    }
}
