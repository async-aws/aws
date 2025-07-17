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
     * The `stateMachineArn` parameter accepts one of the following inputs:
     *
     * - **An unqualified state machine ARN** – Refers to a state machine ARN that isn't qualified with a version or alias
     *   ARN. The following is an example of an unqualified state machine ARN.
     *
     *   `arn:<partition>:states:<region>:<account-id>:stateMachine:<myStateMachine>`
     *
     *   Step Functions doesn't associate state machine executions that you start with an unqualified ARN with a version.
     *   This is true even if that version uses the same revision that the execution used.
     * - **A state machine version ARN** – Refers to a version ARN, which is a combination of state machine ARN and the
     *   version number separated by a colon (:). The following is an example of the ARN for version 10.
     *
     *   `arn:<partition>:states:<region>:<account-id>:stateMachine:<myStateMachine>:10`
     *
     *   Step Functions doesn't associate executions that you start with a version ARN with any aliases that point to that
     *   version.
     * - **A state machine alias ARN** – Refers to an alias ARN, which is a combination of state machine ARN and the alias
     *   name separated by a colon (:). The following is an example of the ARN for an alias named `PROD`.
     *
     *   `arn:<partition>:states:<region>:<account-id>:stateMachine:<myStateMachine:PROD>`
     *
     *   Step Functions associates executions that you start with an alias ARN with that alias and the state machine version
     *   used for that execution.
     *
     * @required
     *
     * @var string|null
     */
    private $stateMachineArn;

    /**
     * Optional name of the execution. This name must be unique for your Amazon Web Services account, Region, and state
     * machine for 90 days. For more information, see Limits Related to State Machine Executions [^1] in the *Step Functions
     * Developer Guide*.
     *
     * If you don't provide a name for the execution, Step Functions automatically generates a universally unique identifier
     * (UUID) as the execution name.
     *
     * A name must *not* contain:
     *
     * - white space
     * - brackets `< > { } [ ]`
     * - wildcard characters `? *`
     * - special characters `" # % \ ^ | ~ ` $ & , ; : /`
     * - control characters (`U+0000-001F`, `U+007F-009F`, `U+FFFE-FFFF`)
     * - surrogates (`U+D800-DFFF`)
     * - invalid characters (` U+10FFFF`)
     *
     * To enable logging with CloudWatch Logs, the name should only contain 0-9, A-Z, a-z, - and _.
     *
     * [^1]: https://docs.aws.amazon.com/step-functions/latest/dg/limits.html#service-limits-state-machine-executions
     *
     * @var string|null
     */
    private $name;

    /**
     * The string that contains the JSON input data for the execution, for example:
     *
     * `"{\"first_name\" : \"Tim\"}"`
     *
     * > If you don't include any JSON input data, you still must include the two braces, for example: `"{}"`
     *
     * Length constraints apply to the payload size, and are expressed as bytes in UTF-8 encoding.
     *
     * @var string|null
     */
    private $input;

    /**
     * Passes the X-Ray trace header. The trace header can also be passed in the request payload.
     *
     * > For X-Ray traces, all Amazon Web Services services use the `X-Amzn-Trace-Id` header from the HTTP request. Using
     * > the header is the preferred mechanism to identify a trace. `StartExecution` and `StartSyncExecution` API operations
     * > can also use `traceHeader` from the body of the request payload. If **both** sources are provided, Step Functions
     * > will use the **header value** (preferred) over the value in the request body.
     *
     * @var string|null
     */
    private $traceHeader;

    /**
     * @param array{
     *   stateMachineArn?: string,
     *   name?: null|string,
     *   input?: null|string,
     *   traceHeader?: null|string,
     *   '@region'?: string|null,
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

    /**
     * @param array{
     *   stateMachineArn?: string,
     *   name?: null|string,
     *   input?: null|string,
     *   traceHeader?: null|string,
     *   '@region'?: string|null,
     * }|StartExecutionInput $input
     */
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
            throw new InvalidArgument(\sprintf('Missing parameter "stateMachineArn" for "%s". The value cannot be null.', __CLASS__));
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
