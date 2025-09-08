<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\ValueObject\HumanLoopConfig;
use AsyncAws\Rekognition\ValueObject\Image;

final class DetectModerationLabelsRequest extends Input
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
     * Specifies the minimum confidence level for the labels to return. Amazon Rekognition doesn't return any labels with a
     * confidence level lower than this specified value.
     *
     * If you don't specify `MinConfidence`, the operation returns labels with confidence values greater than or equal to 50
     * percent.
     *
     * @var float|null
     */
    private $minConfidence;

    /**
     * Sets up the configuration for human evaluation, including the FlowDefinition the image will be sent to.
     *
     * @var HumanLoopConfig|null
     */
    private $humanLoopConfig;

    /**
     * Identifier for the custom adapter. Expects the ProjectVersionArn as a value. Use the CreateProject or
     * CreateProjectVersion APIs to create a custom adapter.
     *
     * @var string|null
     */
    private $projectVersion;

    /**
     * @param array{
     *   Image?: Image|array,
     *   MinConfidence?: float|null,
     *   HumanLoopConfig?: HumanLoopConfig|array|null,
     *   ProjectVersion?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->image = isset($input['Image']) ? Image::create($input['Image']) : null;
        $this->minConfidence = $input['MinConfidence'] ?? null;
        $this->humanLoopConfig = isset($input['HumanLoopConfig']) ? HumanLoopConfig::create($input['HumanLoopConfig']) : null;
        $this->projectVersion = $input['ProjectVersion'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Image?: Image|array,
     *   MinConfidence?: float|null,
     *   HumanLoopConfig?: HumanLoopConfig|array|null,
     *   ProjectVersion?: string|null,
     *   '@region'?: string|null,
     * }|DetectModerationLabelsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHumanLoopConfig(): ?HumanLoopConfig
    {
        return $this->humanLoopConfig;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function getMinConfidence(): ?float
    {
        return $this->minConfidence;
    }

    public function getProjectVersion(): ?string
    {
        return $this->projectVersion;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.DetectModerationLabels',
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

    public function setHumanLoopConfig(?HumanLoopConfig $value): self
    {
        $this->humanLoopConfig = $value;

        return $this;
    }

    public function setImage(?Image $value): self
    {
        $this->image = $value;

        return $this;
    }

    public function setMinConfidence(?float $value): self
    {
        $this->minConfidence = $value;

        return $this;
    }

    public function setProjectVersion(?string $value): self
    {
        $this->projectVersion = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->image) {
            throw new InvalidArgument(\sprintf('Missing parameter "Image" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Image'] = $v->requestBody();
        if (null !== $v = $this->minConfidence) {
            $payload['MinConfidence'] = $v;
        }
        if (null !== $v = $this->humanLoopConfig) {
            $payload['HumanLoopConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->projectVersion) {
            $payload['ProjectVersion'] = $v;
        }

        return $payload;
    }
}
