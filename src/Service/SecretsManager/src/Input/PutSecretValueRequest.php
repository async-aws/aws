<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutSecretValueRequest extends Input
{
    /**
     * The ARN or name of the secret to add a new version to.
     *
     * For an ARN, we recommend that you specify a complete ARN rather than a partial ARN. See Finding a secret from a
     * partial ARN [^1].
     *
     * If the secret doesn't already exist, use `CreateSecret` instead.
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/troubleshoot.html#ARN_secretnamehyphen
     *
     * @required
     *
     * @var string|null
     */
    private $secretId;

    /**
     * A unique identifier for the new version of the secret.
     *
     * > If you use the Amazon Web Services CLI or one of the Amazon Web Services SDKs to call this operation, then you can
     * > leave this parameter empty. The CLI or SDK generates a random UUID for you and includes it as the value for this
     * > parameter in the request.
     *
     * If you generate a raw HTTP request to the Secrets Manager service endpoint, then you must generate a
     * `ClientRequestToken` and include it in the request.
     *
     * This value helps ensure idempotency. Secrets Manager uses this value to prevent the accidental creation of duplicate
     * versions if there are failures and retries during a rotation. We recommend that you generate a UUID-type [^1] value
     * to ensure uniqueness of your versions within the specified secret.
     *
     * - If the `ClientRequestToken` value isn't already associated with a version of the secret then a new version of the
     *   secret is created.
     * - If a version with this value already exists and that version's `SecretString` or `SecretBinary` values are the same
     *   as those in the request then the request is ignored. The operation is idempotent.
     * - If a version with this value already exists and the version of the `SecretString` and `SecretBinary` values are
     *   different from those in the request, then the request fails because you can't modify a secret version. You can only
     *   create new versions to store new secret values.
     *
     * This value becomes the `VersionId` of the new version.
     *
     * [^1]: https://wikipedia.org/wiki/Universally_unique_identifier
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * The binary data to encrypt and store in the new version of the secret. To use this parameter in the command-line
     * tools, we recommend that you store your binary data in a file and then pass the contents of the file as a parameter.
     *
     * You must include `SecretBinary` or `SecretString`, but not both.
     *
     * You can't access this value from the Secrets Manager console.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * The text to encrypt and store in the new version of the secret.
     *
     * You must include `SecretBinary` or `SecretString`, but not both.
     *
     * We recommend you create the secret string as JSON key/value pairs, as shown in the example.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * A list of staging labels to attach to this version of the secret. Secrets Manager uses staging labels to track
     * versions of a secret through the rotation process.
     *
     * If you specify a staging label that's already associated with a different version of the same secret, then Secrets
     * Manager removes the label from the other version and attaches it to this version. If you specify `AWSCURRENT`, and it
     * is already attached to another version, then Secrets Manager also moves the staging label `AWSPREVIOUS` to the
     * version that `AWSCURRENT` was removed from.
     *
     * If you don't include `VersionStages`, then Secrets Manager automatically moves the staging label `AWSCURRENT` to this
     * version.
     *
     * @var string[]|null
     */
    private $versionStages;

    /**
     * A unique identifier that indicates the source of the request. Required for secret rotations using an IAM assumed role
     * or cross-account rotation, in which you rotate a secret in one account by using a Lambda rotation function in another
     * account. In both cases, the rotation function assumes an IAM role to call Secrets Manager, and then Secrets Manager
     * validates the identity using the token. For more information, see How rotation works [^1] and Rotation by Lambda
     * functions [^2].
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/rotating-secrets.html
     * [^2]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/rotate-secrets_lambda
     *
     * @var string|null
     */
    private $rotationToken;

    /**
     * @param array{
     *   SecretId?: string,
     *   ClientRequestToken?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   VersionStages?: string[]|null,
     *   RotationToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->secretId = $input['SecretId'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->secretBinary = $input['SecretBinary'] ?? null;
        $this->secretString = $input['SecretString'] ?? null;
        $this->versionStages = $input['VersionStages'] ?? null;
        $this->rotationToken = $input['RotationToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SecretId?: string,
     *   ClientRequestToken?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   VersionStages?: string[]|null,
     *   RotationToken?: string|null,
     *   '@region'?: string|null,
     * }|PutSecretValueRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getRotationToken(): ?string
    {
        return $this->rotationToken;
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

    public function setRotationToken(?string $value): self
    {
        $this->rotationToken = $value;

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
            throw new InvalidArgument(\sprintf('Missing parameter "SecretId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SecretId'] = $v;
        if (null === $v = $this->clientRequestToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientRequestToken'] = $v;
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
        if (null !== $v = $this->rotationToken) {
            $payload['RotationToken'] = $v;
        }

        return $payload;
    }
}
