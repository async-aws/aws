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
     */
    private $authorizationType;

    /**
     * The Identity and Access Management (IAM) settings.
     */
    private $awsIamConfig;

    /**
     * @param array{
     *   authorizationType: AuthorizationType::*,
     *   awsIamConfig?: null|AwsIamConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->authorizationType = $input['authorizationType'] ?? null;
        $this->awsIamConfig = isset($input['awsIamConfig']) ? AwsIamConfig::create($input['awsIamConfig']) : null;
    }

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
        if (null === $v = $this->authorizationType) {
            throw new InvalidArgument(sprintf('Missing parameter "authorizationType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!AuthorizationType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "authorizationType" for "%s". The value "%s" is not a valid "AuthorizationType".', __CLASS__, $v));
        }
        $payload['authorizationType'] = $v;
        if (null !== $v = $this->awsIamConfig) {
            $payload['awsIamConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
