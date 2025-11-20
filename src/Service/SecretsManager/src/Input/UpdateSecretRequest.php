<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateSecretRequest extends Input
{
    /**
     * The ARN or name of the secret.
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
     * If you include `SecretString` or `SecretBinary`, then Secrets Manager creates a new version for the secret, and this
     * parameter specifies the unique identifier for the new version.
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
     * [^1]: https://wikipedia.org/wiki/Universally_unique_identifier
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * The description of the secret.
     *
     * @var string|null
     */
    private $description;

    /**
     * The ARN, key ID, or alias of the KMS key that Secrets Manager uses to encrypt new secret versions as well as any
     * existing versions with the staging labels `AWSCURRENT`, `AWSPENDING`, or `AWSPREVIOUS`. If you don't have
     * `kms:Encrypt` permission to the new key, Secrets Manager does not re-encrypt existing secret versions with the new
     * key. For more information about versions and staging labels, see Concepts: Version [^1].
     *
     * A key alias is always prefixed by `alias/`, for example `alias/aws/secretsmanager`. For more information, see About
     * aliases [^2].
     *
     * If you set this to an empty string, Secrets Manager uses the Amazon Web Services managed key `aws/secretsmanager`. If
     * this key doesn't already exist in your account, then Secrets Manager creates it for you automatically. All users and
     * roles in the Amazon Web Services account automatically have access to use `aws/secretsmanager`. Creating
     * `aws/secretsmanager` can result in a one-time significant delay in returning the result.
     *
     * ! You can only use the Amazon Web Services managed key `aws/secretsmanager` if you call this operation using
     * ! credentials from the same Amazon Web Services account that owns the secret. If the secret is in a different
     * ! account, then you must use a customer managed key and provide the ARN of that KMS key in this field. The user
     * ! making the call must have permissions to both the secret and the KMS key in their respective accounts.
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/getting-started.html#term_version
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/alias-about.html
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The binary data to encrypt and store in the new version of the secret. We recommend that you store your binary data
     * in a file and then pass the contents of the file as a parameter.
     *
     * Either `SecretBinary` or `SecretString` must have a value, but not both.
     *
     * You can't access this parameter in the Secrets Manager console.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * The text data to encrypt and store in the new version of the secret. We recommend you use a JSON structure of
     * key/value pairs for your secret value.
     *
     * Either `SecretBinary` or `SecretString` must have a value, but not both.
     *
     * Sensitive: This field contains sensitive information, so the service does not include it in CloudTrail log entries.
     * If you create your own log entries, you must also avoid logging the information in this field.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * The exact string that identifies the third-party partner that holds the external secret. For more information, see
     * Managed external secret partners [^1].
     *
     * [^1]: https://docs.aws.amazon.com/secretsmanager/latest/userguide/mes-partners.html
     *
     * @var string|null
     */
    private $type;

    /**
     * @param array{
     *   SecretId?: string,
     *   ClientRequestToken?: string|null,
     *   Description?: string|null,
     *   KmsKeyId?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   Type?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->secretId = $input['SecretId'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->kmsKeyId = $input['KmsKeyId'] ?? null;
        $this->secretBinary = $input['SecretBinary'] ?? null;
        $this->secretString = $input['SecretString'] ?? null;
        $this->type = $input['Type'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SecretId?: string,
     *   ClientRequestToken?: string|null,
     *   Description?: string|null,
     *   KmsKeyId?: string|null,
     *   SecretBinary?: string|null,
     *   SecretString?: string|null,
     *   Type?: string|null,
     *   '@region'?: string|null,
     * }|UpdateSecretRequest $input
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

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.UpdateSecret',
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

    public function setKmsKeyId(?string $value): self
    {
        $this->kmsKeyId = $value;

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

    public function setType(?string $value): self
    {
        $this->type = $value;

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
        if (null !== $v = $this->description) {
            $payload['Description'] = $v;
        }
        if (null !== $v = $this->kmsKeyId) {
            $payload['KmsKeyId'] = $v;
        }
        if (null !== $v = $this->secretBinary) {
            $payload['SecretBinary'] = base64_encode($v);
        }
        if (null !== $v = $this->secretString) {
            $payload['SecretString'] = $v;
        }
        if (null !== $v = $this->type) {
            $payload['Type'] = $v;
        }

        return $payload;
    }
}
