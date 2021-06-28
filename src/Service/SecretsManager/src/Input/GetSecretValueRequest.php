<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetSecretValueRequest extends Input
{
    /**
     * Specifies the secret containing the version that you want to retrieve. You can specify either the Amazon Resource
     * Name (ARN) or the friendly name of the secret.
     *
     * @required
     *
     * @var string|null
     */
    private $secretId;

    /**
     * Specifies the unique identifier of the version of the secret that you want to retrieve. If you specify both this
     * parameter and `VersionStage`, the two parameters must refer to the same secret version. If you don't specify either a
     * `VersionStage` or `VersionId` then the default is to perform the operation on the version with the `VersionStage`
     * value of `AWSCURRENT`.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * Specifies the secret version that you want to retrieve by the staging label attached to the version.
     *
     * @var string|null
     */
    private $versionStage;

    /**
     * @param array{
     *   SecretId?: string,
     *   VersionId?: string,
     *   VersionStage?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->secretId = $input['SecretId'] ?? null;
        $this->versionId = $input['VersionId'] ?? null;
        $this->versionStage = $input['VersionStage'] ?? null;
        parent::__construct($input);
    }

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
            throw new InvalidArgument(sprintf('Missing parameter "SecretId" for "%s". The value cannot be null.', __CLASS__));
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
