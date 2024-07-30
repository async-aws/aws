<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\CredentialProviderType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about credentials that provide access to a private Docker registry. When this is set:
 *
 * - `imagePullCredentialsType` must be set to `SERVICE_ROLE`.
 * - images cannot be curated or an Amazon ECR image.
 *
 * For more information, see Private Registry with Secrets Manager Sample for CodeBuild [^1].
 *
 * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/sample-private-registry.html
 */
final class RegistryCredential
{
    /**
     * The Amazon Resource Name (ARN) or name of credentials created using Secrets Manager.
     *
     * > The `credential` can use the name of the credentials only if they exist in your current Amazon Web Services Region.
     *
     * @var string
     */
    private $credential;

    /**
     * The service that created the credentials to access a private Docker registry. The valid value, SECRETS_MANAGER, is
     * for Secrets Manager.
     *
     * @var CredentialProviderType::*
     */
    private $credentialProvider;

    /**
     * @param array{
     *   credential: string,
     *   credentialProvider: CredentialProviderType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->credential = $input['credential'] ?? $this->throwException(new InvalidArgument('Missing required field "credential".'));
        $this->credentialProvider = $input['credentialProvider'] ?? $this->throwException(new InvalidArgument('Missing required field "credentialProvider".'));
    }

    /**
     * @param array{
     *   credential: string,
     *   credentialProvider: CredentialProviderType::*,
     * }|RegistryCredential $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCredential(): string
    {
        return $this->credential;
    }

    /**
     * @return CredentialProviderType::*
     */
    public function getCredentialProvider(): string
    {
        return $this->credentialProvider;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->credential;
        $payload['credential'] = $v;
        $v = $this->credentialProvider;
        if (!CredentialProviderType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "credentialProvider" for "%s". The value "%s" is not a valid "CredentialProviderType".', __CLASS__, $v));
        }
        $payload['credentialProvider'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
