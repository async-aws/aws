<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateSecretRequest extends Input
{
    /**
     * Specifies the secret that you want to modify or to which you want to add a new version. You can specify either the
     * Amazon Resource Name (ARN) or the friendly name of the secret.
     *
     * @required
     *
     * @var string|null
     */
    private $secretId;

    /**
     * (Optional) If you want to add a new version to the secret, this parameter specifies a unique identifier for the new
     * version that helps ensure idempotency.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * (Optional) Specifies an updated user-provided description of the secret.
     *
     * @var string|null
     */
    private $description;

    /**
     * (Optional) Specifies an updated ARN or alias of the Amazon Web Services KMS customer master key (CMK) to be used to
     * encrypt the protected text in new versions of this secret.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * (Optional) Specifies updated binary data that you want to encrypt and store in the new version of the secret. To use
     * this parameter in the command-line tools, we recommend that you store your binary data in a file and then use the
     * appropriate technique for your tool to pass the contents of the file as a parameter. Either `SecretBinary` or
     * `SecretString` must have a value, but not both. They cannot both be empty.
     *
     * @var string|null
     */
    private $secretBinary;

    /**
     * (Optional) Specifies updated text data that you want to encrypt and store in this new version of the secret. Either
     * `SecretBinary` or `SecretString` must have a value, but not both. They cannot both be empty.
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
        if (null !== $v = $this->clientRequestToken) {
            $payload['ClientRequestToken'] = $v;
        }
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
