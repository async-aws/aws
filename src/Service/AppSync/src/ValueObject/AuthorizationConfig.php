<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\AuthorizationType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The authorization configuration in case the HTTP endpoint requires authorization.
 */
final class AuthorizationConfig
{
    /**
     * The authorization type that the HTTP endpoint requires.
     *
     * - **AWS_IAM**: The authorization type is Signature Version 4 (SigV4).
     *
     * @var AuthorizationType::*
     */
    private $authorizationType;

    /**
     * The Identity and Access Management (IAM) settings.
     *
     * @var AwsIamConfig|null
     */
    private $awsIamConfig;

    /**
     * @param array{
     *   authorizationType: AuthorizationType::*,
     *   awsIamConfig?: AwsIamConfig|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->authorizationType = $input['authorizationType'] ?? $this->throwException(new InvalidArgument('Missing required field "authorizationType".'));
        $this->awsIamConfig = isset($input['awsIamConfig']) ? AwsIamConfig::create($input['awsIamConfig']) : null;
    }

    /**
     * @param array{
     *   authorizationType: AuthorizationType::*,
     *   awsIamConfig?: AwsIamConfig|array|null,
     * }|AuthorizationConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AuthorizationType::*
     */
    public function getAuthorizationType(): string
    {
        return $this->authorizationType;
    }

    public function getAwsIamConfig(): ?AwsIamConfig
    {
        return $this->awsIamConfig;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->authorizationType;
        if (!AuthorizationType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "authorizationType" for "%s". The value "%s" is not a valid "AuthorizationType".', __CLASS__, $v));
        }
        $payload['authorizationType'] = $v;
        if (null !== $v = $this->awsIamConfig) {
            $payload['awsIamConfig'] = $v->requestBody();
        }

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
