<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\InvocationType;
use AsyncAws\Lambda\Enum\LogType;

class InvocationRequest
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
     * @var InvocationType::*|null
     */
    private $InvocationType;

    /**
     * Set to `Tail` to include the execution log in the response.
     *
     * @var LogType::*|null
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

    public function requestBody(): string
    {
        return $this->Payload ?? '';
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/json'];
        if (null !== $this->InvocationType) {
            $headers['X-Amz-Invocation-Type'] = $this->InvocationType;
        }
        if (null !== $this->LogType) {
            $headers['X-Amz-Log-Type'] = $this->LogType;
        }
        if (null !== $this->ClientContext) {
            $headers['X-Amz-Client-Context'] = $this->ClientContext;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->Qualifier) {
            $query['Qualifier'] = $this->Qualifier;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['FunctionName'] = $this->FunctionName ?? '';

        return "/2015-03-31/functions/{$uri['FunctionName']}/invocations";
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

    public function validate(): void
    {
        if (null === $this->FunctionName) {
            throw new InvalidArgument(sprintf('Missing parameter "FunctionName" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null !== $this->InvocationType) {
            if (!InvocationType::exists($this->InvocationType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "InvocationType" when validating the "%s". The value "%s" is not a valid "InvocationType".', __CLASS__, $this->InvocationType));
            }
        }

        if (null !== $this->LogType) {
            if (!LogType::exists($this->LogType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "LogType" when validating the "%s". The value "%s" is not a valid "LogType".', __CLASS__, $this->LogType));
            }
        }
    }
}
