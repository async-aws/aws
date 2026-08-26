<?php

namespace AsyncAws\BedrockAgentCore\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class InvokeAgentRuntimeResponse extends Result
{
    /**
     * The identifier of the runtime session.
     *
     * @var string|null
     */
    private $runtimeSessionId;

    /**
     * The identifier of the MCP session.
     *
     * @var string|null
     */
    private $mcpSessionId;

    /**
     * The version of the MCP protocol being used.
     *
     * @var string|null
     */
    private $mcpProtocolVersion;

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
     * The MIME type of the response data. This indicates how to interpret the response data. Common values include
     * application/json for JSON data.
     *
     * @var string
     */
    private $contentType;

    /**
     * The response data from the agent runtime. The format of this data depends on the specific agent configuration and the
     * requested accept type. For most agents, this is a JSON object containing the agent's response to the user's request.
     *
     * @var string|null
     */
    private $response;

    /**
     * The HTTP status code of the response. A status code of 200 indicates a successful operation. Other status codes
     * indicate various error conditions.
     *
     * @var int|null
     */
    private $statusCode;

    public function getBaggage(): ?string
    {
        $this->initialize();

        return $this->baggage;
    }

    public function getContentType(): string
    {
        $this->initialize();

        return $this->contentType;
    }

    public function getMcpProtocolVersion(): ?string
    {
        $this->initialize();

        return $this->mcpProtocolVersion;
    }

    public function getMcpSessionId(): ?string
    {
        $this->initialize();

        return $this->mcpSessionId;
    }

    public function getResponse(): ?string
    {
        $this->initialize();

        return $this->response;
    }

    public function getRuntimeSessionId(): ?string
    {
        $this->initialize();

        return $this->runtimeSessionId;
    }

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->statusCode;
    }

    public function getTraceId(): ?string
    {
        $this->initialize();

        return $this->traceId;
    }

    public function getTraceParent(): ?string
    {
        $this->initialize();

        return $this->traceParent;
    }

    public function getTraceState(): ?string
    {
        $this->initialize();

        return $this->traceState;
    }

    protected function populateResult(Response $response): void
    {
        $this->statusCode = $response->getStatusCode();
        $headers = $response->getHeaders();

        $this->runtimeSessionId = $headers['x-amzn-bedrock-agentcore-runtime-session-id'][0] ?? null;
        $this->mcpSessionId = $headers['mcp-session-id'][0] ?? null;
        $this->mcpProtocolVersion = $headers['mcp-protocol-version'][0] ?? null;
        $this->traceId = $headers['x-amzn-trace-id'][0] ?? null;
        $this->traceParent = $headers['traceparent'][0] ?? null;
        $this->traceState = $headers['tracestate'][0] ?? null;
        $this->baggage = $headers['baggage'][0] ?? null;
        $this->contentType = $headers['content-type'][0];

        $this->response = $response->getContent();
    }
}
