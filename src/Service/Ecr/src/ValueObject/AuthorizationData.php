<?php

namespace AsyncAws\Ecr\ValueObject;

/**
 * An object representing authorization data for an Amazon ECR registry.
 */
final class AuthorizationData
{
    /**
     * A base64-encoded string that contains authorization data for the specified Amazon ECR registry. When the string is
     * decoded, it is presented in the format `user:password` for private registry authentication using `docker login`.
     *
     * @var string|null
     */
    private $authorizationToken;

    /**
     * The Unix time in seconds and milliseconds when the authorization token expires. Authorization tokens are valid for 12
     * hours.
     *
     * @var \DateTimeImmutable|null
     */
    private $expiresAt;

    /**
     * The registry URL to use for this authorization token in a `docker login` command. The Amazon ECR registry URL format
     * is `https://aws_account_id.dkr.ecr.region.amazonaws.com`. For example,
     * `https://012345678910.dkr.ecr.us-east-1.amazonaws.com`..
     *
     * @var string|null
     */
    private $proxyEndpoint;

    /**
     * @param array{
     *   authorizationToken?: string|null,
     *   expiresAt?: \DateTimeImmutable|null,
     *   proxyEndpoint?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->authorizationToken = $input['authorizationToken'] ?? null;
        $this->expiresAt = $input['expiresAt'] ?? null;
        $this->proxyEndpoint = $input['proxyEndpoint'] ?? null;
    }

    /**
     * @param array{
     *   authorizationToken?: string|null,
     *   expiresAt?: \DateTimeImmutable|null,
     *   proxyEndpoint?: string|null,
     * }|AuthorizationData $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAuthorizationToken(): ?string
    {
        return $this->authorizationToken;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getProxyEndpoint(): ?string
    {
        return $this->proxyEndpoint;
    }
}
