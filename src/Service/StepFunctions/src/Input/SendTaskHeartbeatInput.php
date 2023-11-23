<?php

namespace AsyncAws\StepFunctions\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SendTaskHeartbeatInput extends Input
{
    /**
     * The token that represents this task. Task tokens are generated by Step Functions when tasks are assigned to a worker,
     * or in the context object [^1] when a workflow enters a task state. See GetActivityTaskOutput$taskToken.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/input-output-contextobject.html
     *
     * @required
     *
     * @var string|null
     */
    private $taskToken;

    /**
     * @param array{
     *   taskToken?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->taskToken = $input['taskToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   taskToken?: string,
     *   '@region'?: string|null,
     * }|SendTaskHeartbeatInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTaskToken(): ?string
    {
        return $this->taskToken;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'Accept' => 'application/json',
            'X-Amz-Target' => 'AWSStepFunctions.SendTaskHeartbeat',
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

    public function setTaskToken(?string $value): self
    {
        $this->taskToken = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->taskToken) {
            throw new InvalidArgument(sprintf('Missing parameter "taskToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['taskToken'] = $v;

        return $payload;
    }
}
