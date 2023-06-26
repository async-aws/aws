<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class InvocationResponse extends Result
{
    /**
     * The HTTP status code is in the 200 range for a successful request. For the `RequestResponse` invocation type, this
     * status code is 200. For the `Event` invocation type, this status code is 202. For the `DryRun` invocation type, the
     * status code is 204.
     *
     * @var int|null
     */
    private $statusCode;

    /**
     * If present, indicates that an error occurred during function execution. Details about the error are included in the
     * response payload.
     *
     * @var string|null
     */
    private $functionError;

    /**
     * The last 4 KB of the execution log, which is base64-encoded.
     *
     * @var string|null
     */
    private $logResult;

    /**
     * The response from the function, or an error object.
     *
     * @var string|null
     */
    private $payload;

    /**
     * The version of the function that executed. When you invoke a function with an alias, this indicates which version the
     * alias resolved to.
     *
     * @var string|null
     */
    private $executedVersion;

    public function getExecutedVersion(): ?string
    {
        $this->initialize();

        return $this->executedVersion;
    }

    public function getFunctionError(): ?string
    {
        $this->initialize();

        return $this->functionError;
    }

    public function getLogResult(): ?string
    {
        $this->initialize();

        return $this->logResult;
    }

    public function getPayload(): ?string
    {
        $this->initialize();

        return $this->payload;
    }

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->statusCode;
    }

    protected function populateResult(Response $response): void
    {
        $this->statusCode = $response->getStatusCode();
        $headers = $response->getHeaders();

        $this->functionError = $headers['x-amz-function-error'][0] ?? null;
        $this->logResult = $headers['x-amz-log-result'][0] ?? null;
        $this->executedVersion = $headers['x-amz-executed-version'][0] ?? null;

        $this->payload = $response->getContent();
    }
}
