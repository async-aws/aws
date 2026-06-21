<?php

namespace AsyncAws\ImageBuilder\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartImagePipelineExecutionRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the image pipeline that you want to manually invoke.
     *
     * @required
     *
     * @var string|null
     */
    private $imagePipelineArn;

    /**
     * Unique, case-sensitive identifier you provide to ensure idempotency of the request. For more information, see
     * Ensuring idempotency [^1] in the *Amazon EC2 API Reference*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/Run_Instance_Idempotency.html
     *
     * @required
     *
     * @var string|null
     */
    private $clientToken;

    /**
     * Specify tags for Image Builder to apply to the image resource that's created When it starts pipeline execution.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   imagePipelineArn?: string,
     *   clientToken?: string,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     *   '@responseBuffer'?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->imagePipelineArn = $input['imagePipelineArn'] ?? null;
        $this->clientToken = $input['clientToken'] ?? null;
        $this->tags = $input['tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   imagePipelineArn?: string,
     *   clientToken?: string,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     *   '@responseBuffer'?: bool,
     * }|StartImagePipelineExecutionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientToken(): ?string
    {
        return $this->clientToken;
    }

    public function getImagePipelineArn(): ?string
    {
        return $this->imagePipelineArn;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/StartImagePipelineExecution';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientToken(?string $value): self
    {
        $this->clientToken = $value;

        return $this;
    }

    public function setImagePipelineArn(?string $value): self
    {
        $this->imagePipelineArn = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->imagePipelineArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "imagePipelineArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['imagePipelineArn'] = $v;
        if (null === $v = $this->clientToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['clientToken'] = $v;
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['tags'] = new \stdClass();
            } else {
                $payload['tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['tags'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
