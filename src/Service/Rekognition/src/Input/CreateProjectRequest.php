<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

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
     * @param array{
     *   ProjectName?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->projectName = $input['ProjectName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
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

    public function setProjectName(?string $value): self
    {
        $this->projectName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->projectName) {
            throw new InvalidArgument(sprintf('Missing parameter "ProjectName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ProjectName'] = $v;

        return $payload;
    }
}
