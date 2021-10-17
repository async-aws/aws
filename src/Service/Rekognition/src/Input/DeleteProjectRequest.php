<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteProjectRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the project that you want to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $projectArn;

    /**
     * @param array{
     *   ProjectArn?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->projectArn = $input['ProjectArn'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getProjectArn(): ?string
    {
        return $this->projectArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.DeleteProject',
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

    public function setProjectArn(?string $value): self
    {
        $this->projectArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->projectArn) {
            throw new InvalidArgument(sprintf('Missing parameter "ProjectArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ProjectArn'] = $v;

        return $payload;
    }
}
