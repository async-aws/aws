<?php

namespace AsyncAws\BedrockAgentCore\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class InvokeAgentRuntimeRequest extends Input
{
    /**
     * The MIME type of the input data in the payload. This tells the agent runtime how to interpret the payload data.
     * Common values include application/json for JSON data.
     *
     * @var string|null
     */
    private $contentType;

    /**
     * The desired MIME type for the response from the agent runtime. This tells the agent runtime what format to use for
     * the response data. Common values include application/json for JSON data.
     *
     * @var string|null
     */
    private $accept;

    /**
     * The identifier of the MCP session.
     *
     * @var string|null
     */
    private $mcpSessionId;

    /**
     * The identifier of the runtime session.
     *
     * @var string|null
     */
    private $runtimeSessionId;

    /**
     * The version of the MCP protocol being used.
     *
     * @var string|null
     */
    private $mcpProtocolVersion;

    /**
     * The MCP method being invoked. For example, `tools/call`, `resources/read`, or `prompts/get`.
     *
     * @var string|null
     */
    private $mcpMethod;

    /**
     * The name of the MCP resource, tool, or prompt being accessed. The value depends on the method:
     *
     * - `tools/call` – The tool name.
     * - `resources/read` – The resource URI.
     * - `prompts/get` – The prompt name.
     *
     * @var string|null
     */
    private $mcpName;

    /**
     * The identifier of the runtime user.
     *
     * @var string|null
     */
    private $runtimeUserId;

    /**
     * The trace identifier for request tracking.
     *
     * @var string|null
     */
    private $traceId;

    /**
     * The parent trace information for distributed tracing.
     *
     * @var string|null
     */
    private $traceParent;

    /**
     * The trace state information for distributed tracing.
     *
     * @var string|null
     */
    private $traceState;

    /**
     * Additional context information for distributed tracing.
     *
     * @var string|null
     */
    private $baggage;

    /**
     * The identifier of the agent runtime to invoke. You can specify either the full Amazon Web Services Resource Name
     * (ARN) or the agent ID. If you use the agent ID, you must also provide the `accountId` query parameter.
     *
     * @required
     *
     * @var string|null
     */
    private $agentRuntimeArn;

    /**
     * The qualifier to use for the agent runtime. This is an endpoint name that points to a specific version. If not
     * specified, Amazon Bedrock AgentCore uses the default endpoint of the agent runtime.
     *
     * @var string|null
     */
    private $qualifier;

    /**
     * The identifier of the Amazon Web Services account for the agent runtime resource. This parameter is required when you
     * specify an agent ID instead of the full ARN for `agentRuntimeArn`.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * The input data to send to the agent runtime. The format of this data depends on the specific agent configuration and
     * must match the specified content type. For most agents, this is a JSON object containing the user's request.
     *
     * @required
     *
     * @var string|null
     */
    private $payload;

    /**
     * @param array{
     *   contentType?: string|null,
     *   accept?: string|null,
     *   mcpSessionId?: string|null,
     *   runtimeSessionId?: string|null,
     *   mcpProtocolVersion?: string|null,
     *   mcpMethod?: string|null,
     *   mcpName?: string|null,
     *   runtimeUserId?: string|null,
     *   traceId?: string|null,
     *   traceParent?: string|null,
     *   traceState?: string|null,
     *   baggage?: string|null,
     *   agentRuntimeArn?: string,
     *   qualifier?: string|null,
     *   accountId?: string|null,
     *   payload?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->contentType = $input['contentType'] ?? null;
        $this->accept = $input['accept'] ?? null;
        $this->mcpSessionId = $input['mcpSessionId'] ?? null;
        $this->runtimeSessionId = $input['runtimeSessionId'] ?? null;
        $this->mcpProtocolVersion = $input['mcpProtocolVersion'] ?? null;
        $this->mcpMethod = $input['mcpMethod'] ?? null;
        $this->mcpName = $input['mcpName'] ?? null;
        $this->runtimeUserId = $input['runtimeUserId'] ?? null;
        $this->traceId = $input['traceId'] ?? null;
        $this->traceParent = $input['traceParent'] ?? null;
        $this->traceState = $input['traceState'] ?? null;
        $this->baggage = $input['baggage'] ?? null;
        $this->agentRuntimeArn = $input['agentRuntimeArn'] ?? null;
        $this->qualifier = $input['qualifier'] ?? null;
        $this->accountId = $input['accountId'] ?? null;
        $this->payload = $input['payload'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   contentType?: string|null,
     *   accept?: string|null,
     *   mcpSessionId?: string|null,
     *   runtimeSessionId?: string|null,
     *   mcpProtocolVersion?: string|null,
     *   mcpMethod?: string|null,
     *   mcpName?: string|null,
     *   runtimeUserId?: string|null,
     *   traceId?: string|null,
     *   traceParent?: string|null,
     *   traceState?: string|null,
     *   baggage?: string|null,
     *   agentRuntimeArn?: string,
     *   qualifier?: string|null,
     *   accountId?: string|null,
     *   payload?: string,
     *   '@region'?: string|null,
     * }|InvokeAgentRuntimeRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccept(): ?string
    {
        return $this->accept;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getAgentRuntimeArn(): ?string
    {
        return $this->agentRuntimeArn;
    }

    public function getBaggage(): ?string
    {
        return $this->baggage;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getMcpMethod(): ?string
    {
        return $this->mcpMethod;
    }

    public function getMcpName(): ?string
    {
        return $this->mcpName;
    }

    public function getMcpProtocolVersion(): ?string
    {
        return $this->mcpProtocolVersion;
    }

    public function getMcpSessionId(): ?string
    {
        return $this->mcpSessionId;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function getQualifier(): ?string
    {
        return $this->qualifier;
    }

    public function getRuntimeSessionId(): ?string
    {
        return $this->runtimeSessionId;
    }

    public function getRuntimeUserId(): ?string
    {
        return $this->runtimeUserId;
    }

    public function getTraceId(): ?string
    {
        return $this->traceId;
    }

    public function getTraceParent(): ?string
    {
        return $this->traceParent;
    }

    public function getTraceState(): ?string
    {
        return $this->traceState;
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
        if (null !== $this->contentType) {
            $headers['Content-Type'] = $this->contentType;
        }
        if (null !== $this->accept) {
            $headers['Accept'] = $this->accept;
        }
        if (null !== $this->mcpSessionId) {
            $headers['Mcp-Session-Id'] = $this->mcpSessionId;
        }
        if (null !== $this->runtimeSessionId) {
            $headers['X-Amzn-Bedrock-AgentCore-Runtime-Session-Id'] = $this->runtimeSessionId;
        }
        if (null !== $this->mcpProtocolVersion) {
            $headers['Mcp-Protocol-Version'] = $this->mcpProtocolVersion;
        }
        if (null !== $this->mcpMethod) {
            $headers['Mcp-Method'] = $this->mcpMethod;
        }
        if (null !== $this->mcpName) {
            $headers['Mcp-Name'] = $this->mcpName;
        }
        if (null !== $this->runtimeUserId) {
            $headers['X-Amzn-Bedrock-AgentCore-Runtime-User-Id'] = $this->runtimeUserId;
        }
        if (null !== $this->traceId) {
            $headers['X-Amzn-Trace-Id'] = $this->traceId;
        }
        if (null !== $this->traceParent) {
            $headers['traceparent'] = $this->traceParent;
        }
        if (null !== $this->traceState) {
            $headers['tracestate'] = $this->traceState;
        }
        if (null !== $this->baggage) {
            $headers['baggage'] = $this->baggage;
        }

        // Prepare query
        $query = [];
        if (null !== $this->qualifier) {
            $query['qualifier'] = $this->qualifier;
        }
        if (null !== $this->accountId) {
            $query['accountId'] = $this->accountId;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->agentRuntimeArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "agentRuntimeArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['agentRuntimeArn'] = $v;
        $uriString = '/runtimes/' . rawurlencode($uri['agentRuntimeArn']) . '/invocations';

        // Prepare Body
        if (null === $v = $this->payload) {
            throw new InvalidArgument(\sprintf('Missing parameter "payload" for "%s". The value cannot be null.', __CLASS__));
        }
        $body = $v;

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccept(?string $value): self
    {
        $this->accept = $value;

        return $this;
    }

    public function setAccountId(?string $value): self
    {
        $this->accountId = $value;

        return $this;
    }

    public function setAgentRuntimeArn(?string $value): self
    {
        $this->agentRuntimeArn = $value;

        return $this;
    }

    public function setBaggage(?string $value): self
    {
        $this->baggage = $value;

        return $this;
    }

    public function setContentType(?string $value): self
    {
        $this->contentType = $value;

        return $this;
    }

    public function setMcpMethod(?string $value): self
    {
        $this->mcpMethod = $value;

        return $this;
    }

    public function setMcpName(?string $value): self
    {
        $this->mcpName = $value;

        return $this;
    }

    public function setMcpProtocolVersion(?string $value): self
    {
        $this->mcpProtocolVersion = $value;

        return $this;
    }

    public function setMcpSessionId(?string $value): self
    {
        $this->mcpSessionId = $value;

        return $this;
    }

    public function setPayload(?string $value): self
    {
        $this->payload = $value;

        return $this;
    }

    public function setQualifier(?string $value): self
    {
        $this->qualifier = $value;

        return $this;
    }

    public function setRuntimeSessionId(?string $value): self
    {
        $this->runtimeSessionId = $value;

        return $this;
    }

    public function setRuntimeUserId(?string $value): self
    {
        $this->runtimeUserId = $value;

        return $this;
    }

    public function setTraceId(?string $value): self
    {
        $this->traceId = $value;

        return $this;
    }

    public function setTraceParent(?string $value): self
    {
        $this->traceParent = $value;

        return $this;
    }

    public function setTraceState(?string $value): self
    {
        $this->traceState = $value;

        return $this;
    }
}
