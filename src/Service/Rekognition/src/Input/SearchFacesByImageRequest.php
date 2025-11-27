<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\Enum\QualityFilter;
use AsyncAws\Rekognition\ValueObject\Image;

final class SearchFacesByImageRequest extends Input
{
    /**
     * ID of the collection to search.
     *
     * @required
     *
     * @var string|null
     */
    private $collectionId;

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
     * Maximum number of faces to return. The operation returns the maximum number of faces with the highest confidence in
     * the match.
     *
     * @var int|null
     */
    private $maxFaces;

    /**
     * (Optional) Specifies the minimum confidence in the face match to return. For example, don't return any matches where
     * confidence in matches is less than 70%. The default value is 80%.
     *
     * @var float|null
     */
    private $faceMatchThreshold;

    /**
     * A filter that specifies a quality bar for how much filtering is done to identify faces. Filtered faces aren't
     * searched for in the collection. If you specify `AUTO`, Amazon Rekognition chooses the quality bar. If you specify
     * `LOW`, `MEDIUM`, or `HIGH`, filtering removes all faces that don’t meet the chosen quality bar. The quality bar is
     * based on a variety of common use cases. Low-quality detections can occur for a number of reasons. Some examples are
     * an object that's misidentified as a face, a face that's too blurry, or a face with a pose that's too extreme to use.
     * If you specify `NONE`, no filtering is performed. The default value is `NONE`.
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
     *   MaxFaces?: int|null,
     *   FaceMatchThreshold?: float|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->collectionId = $input['CollectionId'] ?? null;
        $this->image = isset($input['Image']) ? Image::create($input['Image']) : null;
        $this->maxFaces = $input['MaxFaces'] ?? null;
        $this->faceMatchThreshold = $input['FaceMatchThreshold'] ?? null;
        $this->qualityFilter = $input['QualityFilter'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CollectionId?: string,
     *   Image?: Image|array,
     *   MaxFaces?: int|null,
     *   FaceMatchThreshold?: float|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * }|SearchFacesByImageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionId(): ?string
    {
        return $this->collectionId;
    }

    public function getFaceMatchThreshold(): ?float
    {
        return $this->faceMatchThreshold;
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
            'X-Amz-Target' => 'RekognitionService.SearchFacesByImage',
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

    public function setFaceMatchThreshold(?float $value): self
    {
        $this->faceMatchThreshold = $value;

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
        if (null !== $v = $this->maxFaces) {
            $payload['MaxFaces'] = $v;
        }
        if (null !== $v = $this->faceMatchThreshold) {
            $payload['FaceMatchThreshold'] = $v;
        }
        if (null !== $v = $this->qualityFilter) {
            if (!QualityFilter::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "QualityFilter" for "%s". The value "%s" is not a valid "QualityFilter".', __CLASS__, $v));
            }
            $payload['QualityFilter'] = $v;
        }

        return $payload;
    }
}
