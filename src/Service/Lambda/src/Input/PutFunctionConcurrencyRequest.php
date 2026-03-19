<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutFunctionConcurrencyRequest extends Input
{
    /**
     * The name or ARN of the Lambda function.
     *
     * **Name formats**
     *
     * - **Function name** – `my-function`.
     * - **Function ARN** – `arn:aws:lambda:us-west-2:123456789012:function:my-function`.
     * - **Partial ARN** – `123456789012:function:my-function`.
     *
     * The length constraint applies only to the full ARN. If you specify only the function name, it is limited to 64
     * characters in length.
     *
     * @required
     *
     * @var string|null
     */
    private $functionName;

    /**
     * The number of simultaneous executions to reserve for the function.
     *
     * @required
     *
     * @var int|null
     */
    private $reservedConcurrentExecutions;

    /**
     * @param array{
     *   FunctionName?: string,
     *   ReservedConcurrentExecutions?: int,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->reservedConcurrentExecutions = $input['ReservedConcurrentExecutions'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   FunctionName?: string,
     *   ReservedConcurrentExecutions?: int,
     *   '@region'?: string|null,
     * }|PutFunctionConcurrencyRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function getReservedConcurrentExecutions(): ?int
    {
        return $this->reservedConcurrentExecutions;
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

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->functionName) {
            throw new InvalidArgument(\sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['FunctionName'] = $v;
        $uriString = '/2017-10-31/functions/' . rawurlencode($uri['FunctionName']) . '/concurrency';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setFunctionName(?string $value): self
    {
        $this->functionName = $value;

        return $this;
    }

    public function setReservedConcurrentExecutions(?int $value): self
    {
        $this->reservedConcurrentExecutions = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->reservedConcurrentExecutions) {
            throw new InvalidArgument(\sprintf('Missing parameter "ReservedConcurrentExecutions" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ReservedConcurrentExecutions'] = $v;

        return $payload;
    }
}
