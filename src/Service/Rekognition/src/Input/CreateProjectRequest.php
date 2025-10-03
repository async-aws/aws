<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Rekognition\Enum\CustomizationFeature;
use AsyncAws\Rekognition\Enum\ProjectAutoUpdate;

final class CreateProjectRequest extends Input
{
    /**
     * The name of the project to create.
     *
     * @required
     *
     * @var string|null
     */
    private $projectName;

    /**
     * Specifies feature that is being customized. If no value is provided CUSTOM_LABELS is used as a default.
     *
     * @var CustomizationFeature::*|null
     */
    private $feature;

    /**
     * Specifies whether automatic retraining should be attempted for the versions of the project. Automatic retraining is
     * done as a best effort. Required argument for Content Moderation. Applicable only to adapters.
     *
     * @var ProjectAutoUpdate::*|null
     */
    private $autoUpdate;

    /**
     * A set of tags (key-value pairs) that you want to attach to the project.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   ProjectName?: string,
     *   Feature?: CustomizationFeature::*|null,
     *   AutoUpdate?: ProjectAutoUpdate::*|null,
     *   Tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->projectName = $input['ProjectName'] ?? null;
        $this->feature = $input['Feature'] ?? null;
        $this->autoUpdate = $input['AutoUpdate'] ?? null;
        $this->tags = $input['Tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ProjectName?: string,
     *   Feature?: CustomizationFeature::*|null,
     *   AutoUpdate?: ProjectAutoUpdate::*|null,
     *   Tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateProjectRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ProjectAutoUpdate::*|null
     */
    public function getAutoUpdate(): ?string
    {
        return $this->autoUpdate;
    }

    /**
     * @return CustomizationFeature::*|null
     */
    public function getFeature(): ?string
    {
        return $this->feature;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
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
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.CreateProject',
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
     * @param ProjectAutoUpdate::*|null $value
     */
    public function setAutoUpdate(?string $value): self
    {
        $this->autoUpdate = $value;

        return $this;
    }

    /**
     * @param CustomizationFeature::*|null $value
     */
    public function setFeature(?string $value): self
    {
        $this->feature = $value;

        return $this;
    }

    public function setProjectName(?string $value): self
    {
        $this->projectName = $value;

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
        if (null === $v = $this->projectName) {
            throw new InvalidArgument(\sprintf('Missing parameter "ProjectName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ProjectName'] = $v;
        if (null !== $v = $this->feature) {
            if (!CustomizationFeature::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Feature" for "%s". The value "%s" is not a valid "CustomizationFeature".', __CLASS__, $v));
            }
            $payload['Feature'] = $v;
        }
        if (null !== $v = $this->autoUpdate) {
            if (!ProjectAutoUpdate::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "AutoUpdate" for "%s". The value "%s" is not a valid "ProjectAutoUpdate".', __CLASS__, $v));
            }
            $payload['AutoUpdate'] = $v;
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['Tags'] = new \stdClass();
            } else {
                $payload['Tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Tags'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
