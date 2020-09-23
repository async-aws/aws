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
     * @required
     *
     * @var Image|null
     */
    private $Image;

    /**
     * An array of facial attributes you want to be returned. This can be the default list of attributes or all attributes.
     * If you don't specify a value for `Attributes` or if you specify `["DEFAULT"]`, the API returns the following subset
     * of facial attributes: `BoundingBox`, `Confidence`, `Pose`, `Quality`, and `Landmarks`. If you provide `["ALL"]`, all
     * facial attributes are returned, but the operation takes longer to complete.
     *
     * @var null|list<Attribute::*>
     */
    private $Attributes;

    /**
     * @param array{
     *   Image?: Image|array,
     *   Attributes?: list<Attribute::*>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Image = isset($input['Image']) ? Image::create($input['Image']) : null;
        $this->Attributes = $input['Attributes'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Attribute::*>
     */
    public function getAttributes(): array
    {
        return $this->Attributes ?? [];
    }

    public function getImage(): ?Image
    {
        return $this->Image;
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

    /**
     * @param list<Attribute::*> $value
     */
    public function setAttributes(array $value): self
    {
        $this->Attributes = $value;

        return $this;
    }

    public function setImage(?Image $value): self
    {
        $this->Image = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Image) {
            throw new InvalidArgument(sprintf('Missing parameter "Image" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Image'] = $v->requestBody();
        if (null !== $v = $this->Attributes) {
            $index = -1;
            $payload['Attributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!Attribute::exists($listValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "Attributes" for "%s". The value "%s" is not a valid "Attribute".', __CLASS__, $listValue));
                }
                $payload['Attributes'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
