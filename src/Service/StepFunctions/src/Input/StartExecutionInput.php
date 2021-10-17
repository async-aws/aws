<?php

namespace AsyncAws\StepFunctions\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartExecutionInput extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the state machine to execute.
     *
     * @required
     *
     * @var string|null
     */
    private $stateMachineArn;

    /**
     * The name of the execution. This name must be unique for your AWS account, region, and state machine for 90 days. For
     * more information, see  Limits Related to State Machine Executions in the *AWS Step Functions Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/step-functions/latest/dg/limits.html#service-limits-state-machine-executions
     *
     * @var string|null
     */
    private $name;

    /**
     * The string that contains the JSON input data for the execution, for example:.
     *
     * @var string|null
     */
    private $input;

    /**
     * Passes the AWS X-Ray trace header. The trace header can also be passed in the request payload.
     *
     * @var string|null
     */
    private $traceHeader;

    /**
     * @param array{
     *   stateMachineArn?: string,
     *   name?: string,
     *   input?: string,
     *   traceHeader?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->stateMachineArn = $input['stateMachineArn'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->input = $input['input'] ?? null;
        $this->traceHeader = $input['traceHeader'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStateMachineArn(): ?string
    {
        return $this->stateMachineArn;
    }

    public function getTraceHeader(): ?string
    {
        return $this->traceHeader;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AWSStepFunctions.StartExecution',
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

    public function setInput(?string $value): self
    {
        $this->input = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setStateMachineArn(?string $value): self
    {
        $this->stateMachineArn = $value;

        return $this;
    }

    public function setTraceHeader(?string $value): self
    {
        $this->traceHeader = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->stateMachineArn) {
            throw new InvalidArgument(sprintf('Missing parameter "stateMachineArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['stateMachineArn'] = $v;
        if (null !== $v = $this->name) {
            $payload['name'] = $v;
        }
        if (null !== $v = $this->input) {
            $payload['input'] = $v;
        }
        if (null !== $v = $this->traceHeader) {
            $payload['traceHeader'] = $v;
        }

        return $payload;
    }
}
