<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of environment variable. Valid values include:.
 *
 * - `PARAMETER_STORE`: An environment variable stored in Systems Manager Parameter Store. To learn how to specify a
 *   parameter store environment variable, see env/parameter-store in the *CodeBuild User Guide*.
 * - `PLAINTEXT`: An environment variable in plain text format. This is the default value.
 * - `SECRETS_MANAGER`: An environment variable stored in Secrets Manager. To learn how to specify a secrets manager
 *   environment variable, see env/secrets-manager in the *CodeBuild User Guide*.
 *
 * @see https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec.env.parameter-store
 * @see https://docs.aws.amazon.com/codebuild/latest/userguide/build-spec-ref.html#build-spec.env.secrets-manager
 */
final class EnvironmentVariableType
{
    public const PARAMETER_STORE = 'PARAMETER_STORE';
    public const PLAINTEXT = 'PLAINTEXT';
    public const SECRETS_MANAGER = 'SECRETS_MANAGER';

    public static function exists(string $value): bool
    {
        return isset([
            self::PARAMETER_STORE => true,
            self::PLAINTEXT => true,
            self::SECRETS_MANAGER => true,
        ][$value]);
    }
}
