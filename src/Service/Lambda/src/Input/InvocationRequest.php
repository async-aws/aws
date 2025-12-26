<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Lambda\Enum\InvocationType;
use AsyncAws\Lambda\Enum\LogType;

final class InvocationRequest extends Input
{
    /**
     * The name or ARN of the Lambda function, version, or alias.
     *
     * **Name formats**
     *
     * - **Function name** – `my-function` (name-only), `my-function:v1` (with alias).
     * - **Function ARN** – `arn:aws:lambda:us-west-2:123456789012:function:my-function`.
     * - **Partial ARN** – `123456789012:function:my-function`.
     *
     * You can append a version number or alias to any of the formats. The length constraint applies only to the full ARN.
     * If you specify only the function name, it is limited to 64 characters in length.
     *
     * @required
     *
     * @var string|null
     */
    private $functionName;

    /**
     * Choose from the following options.
     *
     * - `RequestResponse` (default) – Invoke the function synchronously. Keep the connection open until the function
     *   returns a response or times out. The API response includes the function response and additional data.
     * - `Event` – Invoke the function asynchronously. Send events that fail multiple times to the function's dead-letter
     *   queue (if one is configured). The API response only includes a status code.
     * - `DryRun` – Validate parameter values and verify that the user or role has permission to invoke the function.
     *
     * @var InvocationType::*|null
     */
    private $invocationType;

    /**
     * Set to `Tail` to include the execution log in the response. Applies to synchronously invoked functions only.
     *
     * @var LogType::*|null
     */
    private $logType;

    /**
     * Up to 3,583 bytes of base64-encoded data about the invoking client to pass to the function in the context object.
     * Lambda passes the `ClientContext` object to your function for synchronous invocations only.
     *
     * @var string|null
     */
    private $clientContext;

    /**
     * Optional unique name for the durable execution. When you start your special function, you can give it a unique name
     * to identify this specific execution. It's like giving a nickname to a task.
     *
     * @var string|null
     */
    private $durableExecutionName;

    /**
     * The JSON that you want to provide to your Lambda function as input. The maximum payload size is 6 MB for synchronous
     * invocations and 1 MB for asynchronous invocations.
     *
     * You can enter the JSON directly. For example, `--payload '{ "key": "value" }'`. You can also specify a file path. For
     * example, `--payload file://payload.json`.
     *
     * @var string|null
     */
    private $payload;

    /**
     * Specify a version or alias to invoke a published version of the function.
     *
     * @var string|null
     */
    private $qualifier;

    /**
     * The identifier of the tenant in a multi-tenant Lambda function.
     *
     * @var string|null
     */
    private $tenantId;

    /**
     * @param array{
     *   FunctionName?: string,
     *   InvocationType?: InvocationType::*|null,
     *   LogType?: LogType::*|null,
     *   ClientContext?: string|null,
     *   DurableExecutionName?: string|null,
     *   Payload?: string|null,
     *   Qualifier?: string|null,
     *   TenantId?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->invocationType = $input['InvocationType'] ?? null;
        $this->logType = $input['LogType'] ?? null;
        $this->clientContext = $input['ClientContext'] ?? null;
        $this->durableExecutionName = $input['DurableExecutionName'] ?? null;
        $this->payload = $input['Payload'] ?? null;
        $this->qualifier = $input['Qualifier'] ?? null;
        $this->tenantId = $input['TenantId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   FunctionName?: string,
     *   InvocationType?: InvocationType::*|null,
     *   LogType?: LogType::*|null,
     *   ClientContext?: string|null,
     *   DurableExecutionName?: string|null,
     *   Payload?: string|null,
     *   Qualifier?: string|null,
     *   TenantId?: string|null,
     *   '@region'?: string|null,
     * }|InvocationRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientContext(): ?string
    {
        return $this->clientContext;
    }

    public function getDurableExecutionName(): ?string
    {
        return $this->durableExecutionName;
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    /**
     * @return InvocationType::*|null
     */
    public function getInvocationType(): ?string
    {
        return $this->invocationType;
    }

    /**
     * @return LogType::*|null
     */
    public function getLogType(): ?string
    {
        return $this->logType;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function getQualifier(): ?string
    {
        return $this->qualifier;
    }

    public function getTenantId(): ?string
    {
        return $this->tenantId;
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
        if (null !== $this->invocationType) {
            if (!InvocationType::exists($this->invocationType)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "InvocationType" for "%s". The value "%s" is not a valid "InvocationType".', __CLASS__, $this->invocationType));
            }
            $headers['X-Amz-Invocation-Type'] = $this->invocationType;
        }
        if (null !== $this->logType) {
            if (!LogType::exists($this->logType)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "LogType" for "%s". The value "%s" is not a valid "LogType".', __CLASS__, $this->logType));
            }
            $headers['X-Amz-Log-Type'] = $this->logType;
        }
        if (null !== $this->clientContext) {
            $headers['X-Amz-Client-Context'] = $this->clientContext;
        }
        if (null !== $this->durableExecutionName) {
            $headers['X-Amz-Durable-Execution-Name'] = $this->durableExecutionName;
        }
        if (null !== $this->tenantId) {
            $headers['X-Amz-Tenant-Id'] = $this->tenantId;
        }

        // Prepare query
        $query = [];
        if (null !== $this->qualifier) {
            $query['Qualifier'] = $this->qualifier;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->functionName) {
            throw new InvalidArgument(\sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['FunctionName'] = $v;
        $uriString = '/2015-03-31/functions/' . rawurlencode($uri['FunctionName']) . '/invocations';

        // Prepare Body
        $body = $this->payload ?? '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientContext(?string $value): self
    {
        $this->clientContext = $value;

        return $this;
    }

    public function setDurableExecutionName(?string $value): self
    {
        $this->durableExecutionName = $value;

        return $this;
    }

    public function setFunctionName(?string $value): self
    {
        $this->functionName = $value;

        return $this;
    }

    /**
     * @param InvocationType::*|null $value
     */
    public function setInvocationType(?string $value): self
    {
        $this->invocationType = $value;

        return $this;
    }

    /**
     * @param LogType::*|null $value
     */
    public function setLogType(?string $value): self
    {
        $this->logType = $value;

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

    public function setTenantId(?string $value): self
    {
        $this->tenantId = $value;

        return $this;
    }
}
