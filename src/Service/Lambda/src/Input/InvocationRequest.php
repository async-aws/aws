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
     * The name of the Lambda function, version, or alias.
     *
     * @required
     *
     * @var string|null
     */
    private $functionName;

    /**
     * Choose from the following options.
     *
     * @var null|InvocationType::*
     */
    private $invocationType;

    /**
     * Set to `Tail` to include the execution log in the response.
     *
     * @var null|LogType::*
     */
    private $logType;

    /**
     * Up to 3583 bytes of base64-encoded data about the invoking client to pass to the function in the context object.
     *
     * @var string|null
     */
    private $clientContext;

    /**
     * The JSON that you want to provide to your Lambda function as input.
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
     * @param array{
     *   FunctionName?: string,
     *   InvocationType?: InvocationType::*,
     *   LogType?: LogType::*,
     *   ClientContext?: string,
     *   Payload?: string,
     *   Qualifier?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->invocationType = $input['InvocationType'] ?? null;
        $this->logType = $input['LogType'] ?? null;
        $this->clientContext = $input['ClientContext'] ?? null;
        $this->payload = $input['Payload'] ?? null;
        $this->qualifier = $input['Qualifier'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientContext(): ?string
    {
        return $this->clientContext;
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];
        if (null !== $this->invocationType) {
            if (!InvocationType::exists($this->invocationType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "InvocationType" for "%s". The value "%s" is not a valid "InvocationType".', __CLASS__, $this->invocationType));
            }
            $headers['X-Amz-Invocation-Type'] = $this->invocationType;
        }
        if (null !== $this->logType) {
            if (!LogType::exists($this->logType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "LogType" for "%s". The value "%s" is not a valid "LogType".', __CLASS__, $this->logType));
            }
            $headers['X-Amz-Log-Type'] = $this->logType;
        }
        if (null !== $this->clientContext) {
            $headers['X-Amz-Client-Context'] = $this->clientContext;
        }

        // Prepare query
        $query = [];
        if (null !== $this->qualifier) {
            $query['Qualifier'] = $this->qualifier;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->functionName) {
            throw new InvalidArgument(sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
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
}
