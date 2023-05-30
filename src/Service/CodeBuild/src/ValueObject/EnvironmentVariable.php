<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\EnvironmentVariableType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about an environment variable for a build project or a build.
 */
final class EnvironmentVariable
{
    /**
     * The name or key of the environment variable.
     */
    private $name;

    /**
     * The value of the environment variable.
     *
     * ! We strongly discourage the use of `PLAINTEXT` environment variables to store sensitive values, especially Amazon
     * ! Web Services secret key IDs and secret access keys. `PLAINTEXT` environment variables can be displayed in plain
     * ! text using the CodeBuild console and the CLI. For sensitive values, we recommend you use an environment variable of
     * ! type `PARAMETER_STORE` or `SECRETS_MANAGER`.
     */
    private $value;

    /**
     * The type of environment variable. Valid values include:.
     *
     * - `PARAMETER_STORE`: An environment variable stored in Systems Manager Parameter Store. To learn how to specify a
     *   parameter store environment variable, see env/parameter-store [^1] in the *CodeBuild User Guide*.
     * -
     * - `PLAINTEXT`: An environment variable in plain text format. This is the default value.
     * -
     * - `SECRETS_MANAGER`: An environment variable stored in Secrets Manager. To learn how to specify a secrets manager
     *   environment variable, see env/secrets-manager [^2] in the *CodeBuild User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec.env.parameter-store
     * [^2]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec.env.secrets-manager
     */
    private $type;

    /**
     * @param array{
     *   name: string,
     *   value: string,
     *   type?: null|EnvironmentVariableType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->value = $input['value'] ?? null;
        $this->type = $input['type'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return EnvironmentVariableType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['name'] = $v;
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['value'] = $v;
        if (null !== $v = $this->type) {
            if (!EnvironmentVariableType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "EnvironmentVariableType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
        }

        return $payload;
    }
}
