<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartSessionRequest extends Input
{
    /**
     * The session description.
     *
     * @var string|null
     */
    private $description;

    /**
     * The workgroup to which the session belongs.
     *
     * @required
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * Contains engine data processing unit (DPU) configuration settings and parameter mappings.
     *
     * @required
     *
     * @var EngineConfiguration|null
     */
    private $engineConfiguration;

    /**
     * The notebook version. This value is supplied automatically for notebook sessions in the Athena console and is not
     * required for programmatic session access. The only valid notebook version is `Athena notebook version 1`. If you
     * specify a value for `NotebookVersion`, you must also specify a value for `NotebookId`. See
     * EngineConfiguration$AdditionalConfigs.
     *
     * @var string|null
     */
    private $notebookVersion;

    /**
     * The idle timeout in minutes for the session.
     *
     * @var int|null
     */
    private $sessionIdleTimeoutInMinutes;

    /**
     * A unique case-sensitive string used to ensure the request to create the session is idempotent (executes only once).
     * If another `StartSessionRequest` is received, the same response is returned and another session is not created. If a
     * parameter has changed, an error is returned.
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
     *   Description?: string|null,
     *   WorkGroup?: string,
     *   EngineConfiguration?: EngineConfiguration|array,
     *   NotebookVersion?: string|null,
     *   SessionIdleTimeoutInMinutes?: int|null,
     *   ClientRequestToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->description = $input['Description'] ?? null;
        $this->workGroup = $input['WorkGroup'] ?? null;
        $this->engineConfiguration = isset($input['EngineConfiguration']) ? EngineConfiguration::create($input['EngineConfiguration']) : null;
        $this->notebookVersion = $input['NotebookVersion'] ?? null;
        $this->sessionIdleTimeoutInMinutes = $input['SessionIdleTimeoutInMinutes'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Description?: string|null,
     *   WorkGroup?: string,
     *   EngineConfiguration?: EngineConfiguration|array,
     *   NotebookVersion?: string|null,
     *   SessionIdleTimeoutInMinutes?: int|null,
     *   ClientRequestToken?: string|null,
     *   '@region'?: string|null,
     * }|StartSessionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEngineConfiguration(): ?EngineConfiguration
    {
        return $this->engineConfiguration;
    }

    public function getNotebookVersion(): ?string
    {
        return $this->notebookVersion;
    }

    public function getSessionIdleTimeoutInMinutes(): ?int
    {
        return $this->sessionIdleTimeoutInMinutes;
    }

    public function getWorkGroup(): ?string
    {
        return $this->workGroup;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonAthena.StartSession',
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

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setEngineConfiguration(?EngineConfiguration $value): self
    {
        $this->engineConfiguration = $value;

        return $this;
    }

    public function setNotebookVersion(?string $value): self
    {
        $this->notebookVersion = $value;

        return $this;
    }

    public function setSessionIdleTimeoutInMinutes(?int $value): self
    {
        $this->sessionIdleTimeoutInMinutes = $value;

        return $this;
    }

    public function setWorkGroup(?string $value): self
    {
        $this->workGroup = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null === $v = $this->workGroup) {
            throw new InvalidArgument(\sprintf('Missing parameter "WorkGroup" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['WorkGroup'] = $v;
        if (null === $v = $this->engineConfiguration) {
            throw new InvalidArgument(\sprintf('Missing parameter "EngineConfiguration" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['EngineConfiguration'] = $v->requestBody();
        if (null !== $v = $this->notebookVersion) {
            $payload['NotebookVersion'] = $v;
        }
        if (null !== $v = $this->sessionIdleTimeoutInMinutes) {
            $payload['SessionIdleTimeoutInMinutes'] = $v;
        }
        if (null !== $v = $this->clientRequestToken) {
            $payload['ClientRequestToken'] = $v;
        }

        return $payload;
    }
}
