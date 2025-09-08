<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Athena\ValueObject\CalculationConfiguration;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartCalculationExecutionRequest extends Input
{
    /**
     * The session ID.
     *
     * @required
     *
     * @var string|null
     */
    private $sessionId;

    /**
     * A description of the calculation.
     *
     * @var string|null
     */
    private $description;

    /**
     * Contains configuration information for the calculation.
     *
     * @var CalculationConfiguration|null
     */
    private $calculationConfiguration;

    /**
     * A string that contains the code of the calculation. Use this parameter instead of CalculationConfiguration$CodeBlock,
     * which is deprecated.
     *
     * @var string|null
     */
    private $codeBlock;

    /**
     * A unique case-sensitive string used to ensure the request to create the calculation is idempotent (executes only
     * once). If another `StartCalculationExecutionRequest` is received, the same response is returned and another
     * calculation is not created. If a parameter has changed, an error is returned.
     *
     * ! This token is listed as not required because Amazon Web Services SDKs (for example the Amazon Web Services SDK for
     * ! Java) auto-generate the token for users. If you are not using the Amazon Web Services SDK or the Amazon Web
     * ! Services CLI, you must provide this token or the action will fail.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * @param array{
     *   SessionId?: string,
     *   Description?: string|null,
     *   CalculationConfiguration?: CalculationConfiguration|array|null,
     *   CodeBlock?: string|null,
     *   ClientRequestToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->sessionId = $input['SessionId'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->calculationConfiguration = isset($input['CalculationConfiguration']) ? CalculationConfiguration::create($input['CalculationConfiguration']) : null;
        $this->codeBlock = $input['CodeBlock'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SessionId?: string,
     *   Description?: string|null,
     *   CalculationConfiguration?: CalculationConfiguration|array|null,
     *   CodeBlock?: string|null,
     *   ClientRequestToken?: string|null,
     *   '@region'?: string|null,
     * }|StartCalculationExecutionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @deprecated
     */
    public function getCalculationConfiguration(): ?CalculationConfiguration
    {
        @trigger_error(\sprintf('The property "CalculationConfiguration" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->calculationConfiguration;
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getCodeBlock(): ?string
    {
        return $this->codeBlock;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonAthena.StartCalculationExecution',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @deprecated
     */
    public function setCalculationConfiguration(?CalculationConfiguration $value): self
    {
        @trigger_error(\sprintf('The property "CalculationConfiguration" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->calculationConfiguration = $value;

        return $this;
    }

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

        return $this;
    }

    public function setCodeBlock(?string $value): self
    {
        $this->codeBlock = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setSessionId(?string $value): self
    {
        $this->sessionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->sessionId) {
            throw new InvalidArgument(\sprintf('Missing parameter "SessionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SessionId'] = $v;
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->calculationConfiguration) {
            @trigger_error(\sprintf('The property "CalculationConfiguration" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
            $payload['CalculationConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->codeBlock) {
            $payload['CodeBlock'] = $v;
        }
        if (null !== $v = $this->clientRequestToken) {
            $payload['ClientRequestToken'] = $v;
        }

        return $payload;
    }
}
