<?php

namespace AsyncAws\StepFunctions\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StopExecutionInput extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the execution to stop.
     *
     * @required
     *
     * @var string|null
     */
    private $executionArn;

    /**
     * The error code of the failure.
     *
     * @var string|null
     */
    private $error;

    /**
     * A more detailed explanation of the cause of the failure.
     *
     * @var string|null
     */
    private $cause;

    /**
     * @param array{
     *   executionArn?: string,
     *   error?: string|null,
     *   cause?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->executionArn = $input['executionArn'] ?? null;
        $this->error = $input['error'] ?? null;
        $this->cause = $input['cause'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   executionArn?: string,
     *   error?: string|null,
     *   cause?: string|null,
     *   '@region'?: string|null,
     * }|StopExecutionInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCause(): ?string
    {
        return $this->cause;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getExecutionArn(): ?string
    {
        return $this->executionArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AWSStepFunctions.StopExecution',
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

    public function setCause(?string $value): self
    {
        $this->cause = $value;

        return $this;
    }

    public function setError(?string $value): self
    {
        $this->error = $value;

        return $this;
    }

    public function setExecutionArn(?string $value): self
    {
        $this->executionArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->executionArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "executionArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['executionArn'] = $v;
        if (null !== $v = $this->error) {
            $payload['error'] = $v;
        }
        if (null !== $v = $this->cause) {
            $payload['cause'] = $v;
        }

        return $payload;
    }
}
