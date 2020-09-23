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
    private $CollectionId;

    /**
     * The input image as base64-encoded bytes or an S3 object. If you use the AWS CLI to call Amazon Rekognition
     * operations, passing base64-encoded image bytes is not supported.
     *
     * @required
     *
     * @var Image|null
     */
    private $Image;

    /**
     * Maximum number of faces to return. The operation returns the maximum number of faces with the highest confidence in
     * the match.
     *
     * @var int|null
     */
    private $MaxFaces;

    /**
     * (Optional) Specifies the minimum confidence in the face match to return. For example, don't return any matches where
     * confidence in matches is less than 70%. The default value is 80%.
     *
     * @var float|null
     */
    private $FaceMatchThreshold;

    /**
     * A filter that specifies a quality bar for how much filtering is done to identify faces. Filtered faces aren't
     * searched for in the collection. If you specify `AUTO`, Amazon Rekognition chooses the quality bar. If you specify
     * `LOW`, `MEDIUM`, or `HIGH`, filtering removes all faces that don’t meet the chosen quality bar. The quality bar is
     * based on a variety of common use cases. Low-quality detections can occur for a number of reasons. Some examples are
     * an object that's misidentified as a face, a face that's too blurry, or a face with a pose that's too extreme to use.
     * If you specify `NONE`, no filtering is performed. The default value is `NONE`.
     *
     * @var null|QualityFilter::*
     */
    private $QualityFilter;

    /**
     * @param array{
     *   CollectionId?: string,
     *   Image?: Image|array,
     *   MaxFaces?: int,
     *   FaceMatchThreshold?: float,
     *   QualityFilter?: QualityFilter::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->CollectionId = $input['CollectionId'] ?? null;
        $this->Image = isset($input['Image']) ? Image::create($input['Image']) : null;
        $this->MaxFaces = $input['MaxFaces'] ?? null;
        $this->FaceMatchThreshold = $input['FaceMatchThreshold'] ?? null;
        $this->QualityFilter = $input['QualityFilter'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCollectionId(): ?string
    {
        return $this->CollectionId;
    }

    public function getFaceMatchThreshold(): ?float
    {
        return $this->FaceMatchThreshold;
    }

    public function getImage(): ?Image
    {
        return $this->Image;
    }

    public function getMaxFaces(): ?int
    {
        return $this->MaxFaces;
    }

    /**
     * @return QualityFilter::*|null
     */
    public function getQualityFilter(): ?string
    {
        return $this->QualityFilter;
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
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCollectionId(?string $value): self
    {
        $this->CollectionId = $value;

        return $this;
    }

    public function setFaceMatchThreshold(?float $value): self
    {
        $this->FaceMatchThreshold = $value;

        return $this;
    }

    public function setImage(?Image $value): self
    {
        $this->Image = $value;

        return $this;
    }

    public function setMaxFaces(?int $value): self
    {
        $this->MaxFaces = $value;

        return $this;
    }

    /**
     * @param QualityFilter::*|null $value
     */
    public function setQualityFilter(?string $value): self
    {
        $this->QualityFilter = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->CollectionId) {
            throw new InvalidArgument(sprintf('Missing parameter "CollectionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['CollectionId'] = $v;
        if (null === $v = $this->Image) {
            throw new InvalidArgument(sprintf('Missing parameter "Image" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Image'] = $v->requestBody();
        if (null !== $v = $this->MaxFaces) {
            $payload['MaxFaces'] = $v;
        }
        if (null !== $v = $this->FaceMatchThreshold) {
            $payload['FaceMatchThreshold'] = $v;
        }
        if (null !== $v = $this->QualityFilter) {
            if (!QualityFilter::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "QualityFilter" for "%s". The value "%s" is not a valid "QualityFilter".', __CLASS__, $v));
            }
            $payload['QualityFilter'] = $v;
        }

        return $payload;
    }
}
