<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutSecretValueRequest extends Input
{
    /**
     * Specifies the secret to which you want to add a new version. You can specify either the Amazon Resource Name (ARN) or
     * the friendly name of the secret. The secret must already exist.
     *
     * @required
     *
     * @var string|null
     */
    private $secretId;

    /**
     * (Optional) Specifies a unique identifier for the new version of the secret.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * (Optional) Specifies binary data that you want to encrypt and store in the new version of the secret. To use this
     * parameter in the command-line tools, we recommend that you store your binary data in a file and then use the
     * appropriate technique for your tool to pass the contents of the file as a parameter. Either `SecretBinary` or
     * `SecretString` must have a value, but not both. They cannot both be empty.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * (Optional) Specifies text data that you want to encrypt and store in this new version of the secret. Either
     * `SecretString` or `SecretBinary` must have a value, but not both. They cannot both be empty.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * (Optional) Specifies a list of staging labels that are attached to this version of the secret. These staging labels
     * are used to track the versions through the rotation process by the Lambda rotation function.
     *
     * @var string[]|null
     */
    private $versionStages;

    /**
     * @param array{
     *   SecretId?: string,
     *   ClientRequestToken?: string,
     *   SecretBinary?: string,
     *   SecretString?: string,
     *   VersionStages?: string[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->secretId = $input['SecretId'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->secretBinary = $input['SecretBinary'] ?? null;
        $this->secretString = $input['SecretString'] ?? null;
        $this->versionStages = $input['VersionStages'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getSecretBinary(): ?string
    {
        return $this->secretBinary;
    }

    public function getSecretId(): ?string
    {
        return $this->secretId;
    }

    public function getSecretString(): ?string
    {
        return $this->secretString;
    }

    /**
     * @return string[]
     */
    public function getVersionStages(): array
    {
        return $this->versionStages ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.PutSecretValue',
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

    public function setSecretBinary(?string $value): self
    {
        $this->secretBinary = $value;

        return $this;
    }

    public function setSecretId(?string $value): self
    {
        $this->secretId = $value;

        return $this;
    }

    public function setSecretString(?string $value): self
    {
        $this->secretString = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setVersionStages(array $value): self
    {
        $this->versionStages = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->secretId) {
            throw new InvalidArgument(sprintf('Missing parameter "SecretId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SecretId'] = $v;
        if (null !== $v = $this->clientRequestToken) {
            $payload['ClientRequestToken'] = $v;
        }
        if (null !== $v = $this->secretBinary) {
            $payload['SecretBinary'] = base64_encode($v);
        }
        if (null !== $v = $this->secretString) {
            $payload['SecretString'] = $v;
        }
        if (null !== $v = $this->versionStages) {
            $index = -1;
            $payload['VersionStages'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['VersionStages'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
