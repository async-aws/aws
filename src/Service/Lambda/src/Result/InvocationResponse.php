<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class InvocationResponse extends Result
{
    private $StatusCode;

    private $FunctionError;

    private $LogResult;

    private $Payload;

    private $ExecutedVersion;

    /**
     * The version of the function that executed. When you invoke a function with an alias, this indicates which version the
     * alias resolved to.
     */
    public function getExecutedVersion(): ?string
    {
        $this->initialize();

        return $this->ExecutedVersion;
    }

    /**
     * If present, indicates that an error occurred during function execution. Details about the error are included in the
     * response payload.
     */
    public function getFunctionError(): ?string
    {
        $this->initialize();

        return $this->FunctionError;
    }

    /**
     * The last 4 KB of the execution log, which is base64 encoded.
     */
    public function getLogResult(): ?string
    {
        $this->initialize();

        return $this->LogResult;
    }

    /**
     * The response from the function, or an error object.
     */
    public function getPayload(): ?string
    {
        $this->initialize();

        return $this->Payload;
    }

    /**
     * The HTTP status code is in the 200 range for a successful request. For the `RequestResponse` invocation type, this
     * status code is 200. For the `Event` invocation type, this status code is 202. For the `DryRun` invocation type, the
     * status code is 204.
     */
    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->StatusCode;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $this->StatusCode = $response->getStatusCode();
        $headers = $response->getHeaders(false);

        $this->FunctionError = $headers['x-amz-function-error'][0] ?? null;
        $this->LogResult = $headers['x-amz-log-result'][0] ?? null;
        $this->ExecutedVersion = $headers['x-amz-executed-version'][0] ?? null;

        $this->Payload = $response->getContent(false);
    }
}
