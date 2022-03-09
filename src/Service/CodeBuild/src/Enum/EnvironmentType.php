<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * The type of build environment to use for related builds.
 *
 * - The environment type `ARM_CONTAINER` is available only in regions US East (N. Virginia), US East (Ohio), US West
 *   (Oregon), EU (Ireland), Asia Pacific (Mumbai), Asia Pacific (Tokyo), Asia Pacific (Sydney), and EU (Frankfurt).
 * - The environment type `LINUX_CONTAINER` with compute type `build.general1.2xlarge` is available only in regions US
 *   East (N. Virginia), US East (Ohio), US West (Oregon), Canada (Central), EU (Ireland), EU (London), EU (Frankfurt),
 *   Asia Pacific (Tokyo), Asia Pacific (Seoul), Asia Pacific (Singapore), Asia Pacific (Sydney), China (Beijing), and
 *   China (Ningxia).
 * - The environment type `LINUX_GPU_CONTAINER` is available only in regions US East (N. Virginia), US East (Ohio), US
 *   West (Oregon), Canada (Central), EU (Ireland), EU (London), EU (Frankfurt), Asia Pacific (Tokyo), Asia Pacific
 *   (Seoul), Asia Pacific (Singapore), Asia Pacific (Sydney) , China (Beijing), and China (Ningxia).
 *
 * - The environment types `WINDOWS_CONTAINER` and `WINDOWS_SERVER_2019_CONTAINER` are available only in regions US East
 *   (N. Virginia), US East (Ohio), US West (Oregon), and EU (Ireland).
 *
 * For more information, see Build environment compute types in the *CodeBuild user guide*.
 *
 * @see https://docs.aws.amazon.com/codebuild/latest/userguide/build-env-ref-compute-types.html
 */
final class EnvironmentType
{
    public const ARM_CONTAINER = 'ARM_CONTAINER';
    public const LINUX_CONTAINER = 'LINUX_CONTAINER';
    public const LINUX_GPU_CONTAINER = 'LINUX_GPU_CONTAINER';
    public const WINDOWS_CONTAINER = 'WINDOWS_CONTAINER';
    public const WINDOWS_SERVER_2019_CONTAINER = 'WINDOWS_SERVER_2019_CONTAINER';

    public static function exists(string $value): bool
    {
        return isset([
            self::ARM_CONTAINER => true,
            self::LINUX_CONTAINER => true,
            self::LINUX_GPU_CONTAINER => true,
            self::WINDOWS_CONTAINER => true,
            self::WINDOWS_SERVER_2019_CONTAINER => true,
        ][$value]);
    }
}
