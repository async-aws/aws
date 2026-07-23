<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\Enum\QualityFilter;
use AsyncAws\Rekognition\ValueObject\Image;

final class CompareFacesRequest extends Input
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
    private $sourceImage;

    /**
     * The target image as base64-encoded bytes or an S3 object. If you use the AWS CLI to call Amazon Rekognition
     * operations, passing base64-encoded image bytes is not supported.
     *
     * If you are using an AWS SDK to call Amazon Rekognition, you might not need to base64-encode image bytes passed using
     * the `Bytes` field. For more information, see Images in the Amazon Rekognition developer guide.
     *
     * @required
     *
     * @var Image|null
     */
    private $targetImage;

    /**
     * The minimum level of confidence in the face matches that a match must meet to be included in the `FaceMatches` array.
     *
     * @var float|null
     */
    private $similarityThreshold;

    /**
     * A filter that specifies a quality bar for how much filtering is done to identify faces. Filtered faces aren't
     * compared. If you specify `AUTO`, Amazon Rekognition chooses the quality bar. If you specify `LOW`, `MEDIUM`, or
     * `HIGH`, filtering removes all faces that don’t meet the chosen quality bar. The quality bar is based on a variety
     * of common use cases. Low-quality detections can occur for a number of reasons. Some examples are an object that's
     * misidentified as a face, a face that's too blurry, or a face with a pose that's too extreme to use. If you specify
     * `NONE`, no filtering is performed. The default value is `NONE`.
     *
     * To use quality filtering, the collection you are using must be associated with version 3 of the face model or higher.
     *
     * @var QualityFilter::*|null
     */
    private $qualityFilter;

    /**
     * @param array{
     *   SourceImage?: Image|array,
     *   TargetImage?: Image|array,
     *   SimilarityThreshold?: float|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->sourceImage = isset($input['SourceImage']) ? Image::create($input['SourceImage']) : null;
        $this->targetImage = isset($input['TargetImage']) ? Image::create($input['TargetImage']) : null;
        $this->similarityThreshold = $input['SimilarityThreshold'] ?? null;
        $this->qualityFilter = $input['QualityFilter'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SourceImage?: Image|array,
     *   TargetImage?: Image|array,
     *   SimilarityThreshold?: float|null,
     *   QualityFilter?: QualityFilter::*|null,
     *   '@region'?: string|null,
     * }|CompareFacesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return QualityFilter::*|null
     */
    public function getQualityFilter(): ?string
    {
        return $this->qualityFilter;
    }

    public function getSimilarityThreshold(): ?float
    {
        return $this->similarityThreshold;
    }

    public function getSourceImage(): ?Image
    {
        return $this->sourceImage;
    }

    public function getTargetImage(): ?Image
    {
        return $this->targetImage;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.CompareFaces',
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
     * @param QualityFilter::*|null $value
     */
    public function setQualityFilter(?string $value): self
    {
        $this->qualityFilter = $value;

        return $this;
    }

    public function setSimilarityThreshold(?float $value): self
    {
        $this->similarityThreshold = $value;

        return $this;
    }

    public function setSourceImage(?Image $value): self
    {
        $this->sourceImage = $value;

        return $this;
    }

    public function setTargetImage(?Image $value): self
    {
        $this->targetImage = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->sourceImage) {
            throw new InvalidArgument(\sprintf('Missing parameter "SourceImage" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SourceImage'] = $v->requestBody();
        if (null === $v = $this->targetImage) {
            throw new InvalidArgument(\sprintf('Missing parameter "TargetImage" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TargetImage'] = $v->requestBody();
        if (null !== $v = $this->similarityThreshold) {
            $payload['SimilarityThreshold'] = $v;
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
