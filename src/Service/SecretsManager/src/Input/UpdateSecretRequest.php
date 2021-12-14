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
     * @required
     *
     * @var string|null
     */
    private $secretId;

    /**
     * If you include `SecretString` or `SecretBinary`, then Secrets Manager creates a new version for the secret, and this
     * parameter specifies the unique identifier for the new version.
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
     * existing versions the staging labels `AWSCURRENT`, `AWSPENDING`, or `AWSPREVIOUS`. For more information about
     * versions and staging labels, see Concepts: Version.
     *
     * @see https://docs.aws.amazon.com/secretsmanager/latest/userguide/getting-started.html#term_version
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The binary data to encrypt and store in the new version of the secret. We recommend that you store your binary data
     * in a file and then pass the contents of the file as a parameter.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * The text data to encrypt and store in the new version of the secret. We recommend you use a JSON structure of
     * key/value pairs for your secret value.
     *
     * @var string|null
     */
    private $secretString;

    /**
     * @param array{
     *   SecretId?: string,
     *   ClientRequestToken?: string,
     *   Description?: string,
     *   KmsKeyId?: string,
     *   SecretBinary?: string,
     *   SecretString?: string,
     *   @region?: string,
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.UpdateSecret',
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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->secretId) {
            throw new InvalidArgument(sprintf('Missing parameter "SecretId" for "%s". The value cannot be null.', __CLASS__));
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

        return $payload;
    }
}
