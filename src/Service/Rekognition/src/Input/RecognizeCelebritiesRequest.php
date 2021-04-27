<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\ValueObject\Image;

final class RecognizeCelebritiesRequest extends Input
{
    /**
     * The input image as base64-encoded bytes or an S3 object. If you use the AWS CLI to call Amazon Rekognition
     * operations, passing base64-encoded image bytes is not supported.
     *
     * @required
     *
     * @var Image|null
     */
    private $image;

    /**
     * @param array{
     *   Image?: Image|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->image = isset($input['Image']) ? Image::create($input['Image']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
            'X-Amz-Target' => 'RekognitionService.RecognizeCelebrities',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : \json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
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
            throw new InvalidArgument(sprintf('Missing parameter "Image" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Image'] = $v->requestBody();

        return $payload;
    }
}
