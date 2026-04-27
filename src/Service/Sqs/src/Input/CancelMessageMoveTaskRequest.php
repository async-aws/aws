<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CancelMessageMoveTaskRequest extends Input
{
    /**
     * An identifier associated with a message movement task.
     *
     * @required
     *
     * @var string|null
     */
    private $taskHandle;

    /**
     * @param array{
     *   TaskHandle?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->taskHandle = $input['TaskHandle'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TaskHandle?: string,
     *   '@region'?: string|null,
     * }|CancelMessageMoveTaskRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTaskHandle(): ?string
    {
        return $this->taskHandle;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.CancelMessageMoveTask',
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

    public function setTaskHandle(?string $value): self
    {
        $this->taskHandle = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->taskHandle) {
            throw new InvalidArgument(\sprintf('Missing parameter "TaskHandle" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TaskHandle'] = $v;

        return $payload;
    }
}
