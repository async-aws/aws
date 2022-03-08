<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * Information about the compute resources the build project uses. Available values include:.
 *
 * - `BUILD_GENERAL1_SMALL`: Use up to 3 GB memory and 2 vCPUs for builds.
 * - `BUILD_GENERAL1_MEDIUM`: Use up to 7 GB memory and 4 vCPUs for builds.
 * - `BUILD_GENERAL1_LARGE`: Use up to 16 GB memory and 8 vCPUs for builds, depending on your environment type.
 * - `BUILD_GENERAL1_2XLARGE`: Use up to 145 GB memory, 72 vCPUs, and 824 GB of SSD storage for builds. This compute
 *   type supports Docker images up to 100 GB uncompressed.
 *
 * If you use `BUILD_GENERAL1_LARGE`:
 *
 * - For environment type `LINUX_CONTAINER`, you can use up to 15 GB memory and 8 vCPUs for builds.
 * - For environment type `LINUX_GPU_CONTAINER`, you can use up to 255 GB memory, 32 vCPUs, and 4 NVIDIA Tesla V100 GPUs
 *   for builds.
 * - For environment type `ARM_CONTAINER`, you can use up to 16 GB memory and 8 vCPUs on ARM-based processors for
 *   builds.
 *
 * For more information, see Build Environment Compute Types in the *CodeBuild User Guide.*
 *
 * @see https://docs.aws.amazon.com/codebuild/latest/userguide/build-env-ref-compute-types.html
 */
final class ComputeType
{
    public const BUILD_GENERAL1_2XLARGE = 'BUILD_GENERAL1_2XLARGE';
    public const BUILD_GENERAL1_LARGE = 'BUILD_GENERAL1_LARGE';
    public const BUILD_GENERAL1_MEDIUM = 'BUILD_GENERAL1_MEDIUM';
    public const BUILD_GENERAL1_SMALL = 'BUILD_GENERAL1_SMALL';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUILD_GENERAL1_2XLARGE => true,
            self::BUILD_GENERAL1_LARGE => true,
            self::BUILD_GENERAL1_MEDIUM => true,
            self::BUILD_GENERAL1_SMALL => true,
        ][$value]);
    }
}
