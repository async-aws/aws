<?php

namespace AsyncAws\SecretsManager\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteSecretRequest extends Input
{
    /**
     * The ARN or name of the secret to delete.
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
     * The number of days from 7 to 30 that Secrets Manager waits before permanently deleting the secret. You can't use both
     * this parameter and `ForceDeleteWithoutRecovery` in the same call. If you don't use either, then by default Secrets
     * Manager uses a 30 day recovery window.
     *
     * @var int|null
     */
    private $recoveryWindowInDays;

    /**
     * Specifies whether to delete the secret without any recovery window. You can't use both this parameter and
     * `RecoveryWindowInDays` in the same call. If you don't use either, then by default Secrets Manager uses a 30 day
     * recovery window.
     *
     * Secrets Manager performs the actual deletion with an asynchronous background process, so there might be a short delay
     * before the secret is permanently deleted. If you delete a secret and then immediately create a secret with the same
     * name, use appropriate back off and retry logic.
     *
     * If you forcibly delete an already deleted or nonexistent secret, the operation does not return
     * `ResourceNotFoundException`.
     *
     * ! Use this parameter with caution. This parameter causes the operation to skip the normal recovery window before the
     * ! permanent deletion that Secrets Manager would normally impose with the `RecoveryWindowInDays` parameter. If you
     * ! delete a secret with the `ForceDeleteWithoutRecovery` parameter, then you have no opportunity to recover the
     * ! secret. You lose the secret permanently.
     *
     * @var bool|null
     */
    private $forceDeleteWithoutRecovery;

    /**
     * @param array{
     *   SecretId?: string,
     *   RecoveryWindowInDays?: int|null,
     *   ForceDeleteWithoutRecovery?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->secretId = $input['SecretId'] ?? null;
        $this->recoveryWindowInDays = $input['RecoveryWindowInDays'] ?? null;
        $this->forceDeleteWithoutRecovery = $input['ForceDeleteWithoutRecovery'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SecretId?: string,
     *   RecoveryWindowInDays?: int|null,
     *   ForceDeleteWithoutRecovery?: bool|null,
     *   '@region'?: string|null,
     * }|DeleteSecretRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getForceDeleteWithoutRecovery(): ?bool
    {
        return $this->forceDeleteWithoutRecovery;
    }

    public function getRecoveryWindowInDays(): ?int
    {
        return $this->recoveryWindowInDays;
    }

    public function getSecretId(): ?string
    {
        return $this->secretId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'secretsmanager.DeleteSecret',
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

    public function setForceDeleteWithoutRecovery(?bool $value): self
    {
        $this->forceDeleteWithoutRecovery = $value;

        return $this;
    }

    public function setRecoveryWindowInDays(?int $value): self
    {
        $this->recoveryWindowInDays = $value;

        return $this;
    }

    public function setSecretId(?string $value): self
    {
        $this->secretId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->secretId) {
            throw new InvalidArgument(\sprintf('Missing parameter "SecretId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SecretId'] = $v;
        if (null !== $v = $this->recoveryWindowInDays) {
            $payload['RecoveryWindowInDays'] = $v;
        }
        if (null !== $v = $this->forceDeleteWithoutRecovery) {
            $payload['ForceDeleteWithoutRecovery'] = (bool) $v;
        }

        return $payload;
    }
}
