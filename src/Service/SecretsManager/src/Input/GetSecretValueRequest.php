<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetSecretValueRequest extends Input
{
    /**
     * The ARN or name of the secret to retrieve. To retrieve a secret from another account, you must use an ARN.
     *
     * For an ARN, we recommend that you specify a complete ARN rather than a partial ARN. See Finding a secret from a
     * partial ARN [^1].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/troubleshoot.html#ARN_secretnamehyphen
     *
     * @required
     *
     * @var string|null
     */
    private $secretId;

    /**
     * The unique identifier of the version of the secret to retrieve. If you include both this parameter and
     * `VersionStage`, the two parameters must refer to the same secret version. If you don't specify either a
     * `VersionStage` or `VersionId`, then Secrets Manager returns the `AWSCURRENT` version.
     *
     * This value is typically a UUID-type [^1] value with 32 hexadecimal digits.
     *
     * [^1]: https://wikipedia.org/wiki/Universally_unique_identifier
     *
     * @var string|null
     */
    private $versionId;

    /**
     * The staging label of the version of the secret to retrieve.
     *
     * Secrets Manager uses staging labels to keep track of different versions during the rotation process. If you include
     * both this parameter and `VersionId`, the two parameters must refer to the same secret version. If you don't specify
     * either a `VersionStage` or `VersionId`, Secrets Manager returns the `AWSCURRENT` version.
     *
     * @var string|null
     */
    private $versionStage;

    /**
     * @param array{
     *   SecretId?: string,
     *   VersionId?: string|null,
     *   VersionStage?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->secretId = $input['SecretId'] ?? null;
        $this->versionId = $input['VersionId'] ?? null;
        $this->versionStage = $input['VersionStage'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SecretId?: string,
     *   VersionId?: string|null,
     *   VersionStage?: string|null,
     *   '@region'?: string|null,
     * }|GetSecretValueRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSecretId(): ?string
    {
        return $this->secretId;
    }

    public function getVersionId(): ?string
    {
        return $this->versionId;
    }

    public function getVersionStage(): ?string
    {
        return $this->versionStage;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.GetSecretValue',
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

    public function setSecretId(?string $value): self
    {
        $this->secretId = $value;

        return $this;
    }

    public function setVersionId(?string $value): self
    {
        $this->versionId = $value;

        return $this;
    }

    public function setVersionStage(?string $value): self
    {
        $this->versionStage = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->secretId) {
            throw new InvalidArgument(\sprintf('Missing parameter "SecretId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SecretId'] = $v;
        if (null !== $v = $this->versionId) {
            $payload['VersionId'] = $v;
        }
        if (null !== $v = $this->versionStage) {
            $payload['VersionStage'] = $v;
        }

        return $payload;
    }
}
