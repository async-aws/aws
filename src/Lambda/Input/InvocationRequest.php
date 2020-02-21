<?php

namespace AsyncAws\Lambda\Input;

use AsyncAws\Core\Exception\InvalidArgument;

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
     * @var string|null
     */
    private $InvocationType;

    /**
     * Set to `Tail` to include the execution log in the response.
     *
     * @var string|null
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
     *   InvocationType?: string,
     *   LogType?: string,
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

    public function getInvocationType(): ?string
    {
        return $this->InvocationType;
    }

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
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;

        if (null !== $input = $this->Payload) {
            $document->appendChild($document->createElement('Payload', $input));
        }

        return $document->saveXML();
    }

    public function requestHeaders(): array
    {
        $headers = [];
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

    public function setInvocationType(?string $value): self
    {
        $this->InvocationType = $value;

        return $this;
    }

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
        foreach (['FunctionName'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
