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
     *
     * @var string
     */
    private $name;

    /**
     * The value of the environment variable.
     *
     * ! We strongly discourage the use of `PLAINTEXT` environment variables to store sensitive values, especially Amazon
     * ! Web Services secret key IDs. `PLAINTEXT` environment variables can be displayed in plain text using the CodeBuild
     * ! console and the CLI. For sensitive values, we recommend you use an environment variable of type `PARAMETER_STORE`
     * ! or `SECRETS_MANAGER`.
     *
     * @var string
     */
    private $value;

    /**
     * The type of environment variable. Valid values include:
     *
     * - `PARAMETER_STORE`: An environment variable stored in Systems Manager Parameter Store. For environment variables of
     *   this type, specify the name of the parameter as the `value` of the EnvironmentVariable. The parameter value will be
     *   substituted for the name at runtime. You can also define Parameter Store environment variables in the buildspec. To
     *   learn how to do so, see env/parameter-store [^1] in the *CodeBuild User Guide*.
     * - `PLAINTEXT`: An environment variable in plain text format. This is the default value.
     * - `SECRETS_MANAGER`: An environment variable stored in Secrets Manager. For environment variables of this type,
     *   specify the name of the secret as the `value` of the EnvironmentVariable. The secret value will be substituted for
     *   the name at runtime. You can also define Secrets Manager environment variables in the buildspec. To learn how to do
     *   so, see env/secrets-manager [^2] in the *CodeBuild User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec.env.parameter-store
     * [^2]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec.env.secrets-manager
     *
     * @var EnvironmentVariableType::*|null
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
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->value = $input['value'] ?? $this->throwException(new InvalidArgument('Missing required field "value".'));
        $this->type = $input['type'] ?? null;
    }

    /**
     * @param array{
     *   name: string,
     *   value: string,
     *   type?: null|EnvironmentVariableType::*,
     * }|EnvironmentVariable $input
     */
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
        $v = $this->name;
        $payload['name'] = $v;
        $v = $this->value;
        $payload['value'] = $v;
        if (null !== $v = $this->type) {
            if (!EnvironmentVariableType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "EnvironmentVariableType".', __CLASS__, $v));
            }
            $payload['type'] = $v;
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
