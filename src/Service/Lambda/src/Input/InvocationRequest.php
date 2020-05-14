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
    private $FunctionName;

    /**
     * Choose from the following options.
     *
     * @var null|InvocationType::*
     */
    private $InvocationType;

    /**
     * Set to `Tail` to include the execution log in the response.
     *
     * @var null|LogType::*
     */
    private $LogType;

    /**
     * Up to 3583 bytes of base64-encoded data about the invoking client to pass to the function in the context object.
     *
     * @var string|null
     */
    private $ClientContext;

    /**
     * The JSON that you want to provide to your Lambda function as input.
     *
     * @var string|null
     */
    private $Payload;

    /**
     * Specify a version or alias to invoke a published version of the function.
     *
     * @var string|null
     */
    private $Qualifier;

    /**
     * @param array{
     *   FunctionName?: string,
     *   InvocationType?: \AsyncAws\Lambda\Enum\InvocationType::*,
     *   LogType?: \AsyncAws\Lambda\Enum\LogType::*,
     *   ClientContext?: string,
     *   Payload?: string,
     *   Qualifier?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->FunctionName = $input['FunctionName'] ?? null;
        $this->InvocationType = $input['InvocationType'] ?? null;
        $this->LogType = $input['LogType'] ?? null;
        $this->ClientContext = $input['ClientContext'] ?? null;
        $this->Payload = $input['Payload'] ?? null;
        $this->Qualifier = $input['Qualifier'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientContext(): ?string
    {
        return $this->ClientContext;
    }

    public function getFunctionName(): ?string
    {
        return $this->FunctionName;
    }

    /**
     * @return InvocationType::*|null
     */
    public function getInvocationType(): ?string
    {
        return $this->InvocationType;
    }

    /**
     * @return LogType::*|null
     */
    public function getLogType(): ?string
    {
        return $this->LogType;
    }

    public function getPayload(): ?string
    {
        return $this->Payload;
    }

    public function getQualifier(): ?string
    {
        return $this->Qualifier;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];
        if (null !== $this->InvocationType) {
            if (!InvocationType::exists($this->InvocationType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "InvocationType" for "%s". The value "%s" is not a valid "InvocationType".', __CLASS__, $this->InvocationType));
            }
            $headers['X-Amz-Invocation-Type'] = $this->InvocationType;
        }
        if (null !== $this->LogType) {
            if (!LogType::exists($this->LogType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "LogType" for "%s". The value "%s" is not a valid "LogType".', __CLASS__, $this->LogType));
            }
            $headers['X-Amz-Log-Type'] = $this->LogType;
        }
        if (null !== $this->ClientContext) {
            $headers['X-Amz-Client-Context'] = $this->ClientContext;
        }

        // Prepare query
        $query = [];
        if (null !== $this->Qualifier) {
            $query['Qualifier'] = $this->Qualifier;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->FunctionName) {
            throw new InvalidArgument(sprintf('Missing parameter "FunctionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['FunctionName'] = $v;
        $uriString = '/2015-03-31/functions/' . rawurlencode($uri['FunctionName']) . '/invocations';

        // Prepare Body
        $body = $this->Payload ?? '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientContext(?string $value): self
    {
        $this->ClientContext = $value;

        return $this;
    }

    public function setFunctionName(?string $value): self
    {
        $this->FunctionName = $value;

        return $this;
    }

    /**
     * @param InvocationType::*|null $value
     */
    public function setInvocationType(?string $value): self
    {
        $this->InvocationType = $value;

        return $this;
    }

    /**
     * @param LogType::*|null $value
     */
    public function setLogType(?string $value): self
    {
        $this->LogType = $value;

        return $this;
    }

    public function setPayload(?string $value): self
    {
        $this->Payload = $value;

        return $this;
    }

    public function setQualifier(?string $value): self
    {
        $this->Qualifier = $value;

        return $this;
    }
}
