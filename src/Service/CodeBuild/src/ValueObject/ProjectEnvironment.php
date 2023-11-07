<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\ComputeType;
use AsyncAws\CodeBuild\Enum\EnvironmentType;
use AsyncAws\CodeBuild\Enum\ImagePullCredentialsType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the build environment of the build project.
 */
final class ProjectEnvironment
{
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
     * - The environment types `ARM_LAMBDA_CONTAINER` and `LINUX_LAMBDA_CONTAINER` are available only in regions US East (N.
     *   Virginia), US East (Ohio), US West (Oregon), Asia Pacific (Mumbai), Asia Pacific (Singapore), Asia Pacific
     *   (Sydney), Asia Pacific (Tokyo), EU (Frankfurt), EU (Ireland), and South America (São Paulo).
     *
     * - The environment types `WINDOWS_CONTAINER` and `WINDOWS_SERVER_2019_CONTAINER` are available only in regions US East
     *   (N. Virginia), US East (Ohio), US West (Oregon), and EU (Ireland).
     *
     * For more information, see Build environment compute types [^1] in the *CodeBuild user guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-env-ref-compute-types.html
     *
     * @var EnvironmentType::*
     */
    private $type;

    /**
     * The image tag or image digest that identifies the Docker image to use for this build project. Use the following
     * formats:.
     *
     * - For an image tag: `<registry>/<repository>:<tag>`. For example, in the Docker repository that
     *   CodeBuild uses to manage its Docker images, this would be `aws/codebuild/standard:4.0`.
     * - For an image digest: `<registry>/<repository>@<digest>`. For example, to specify an image with
     *   the digest "sha256:cbbf2f9a99b47fc460d422812b6a5adff7dfee951d8fa2e4a98caa0382cfbdbf," use
     *   `<registry>/<repository>@sha256:cbbf2f9a99b47fc460d422812b6a5adff7dfee951d8fa2e4a98caa0382cfbdbf`.
     *
     * For more information, see Docker images provided by CodeBuild [^1] in the *CodeBuild user guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-env-ref-available.html
     *
     * @var string
     */
    private $image;

    /**
     * Information about the compute resources the build project uses. Available values include:.
     *
     * - `BUILD_GENERAL1_SMALL`: Use up to 3 GB memory and 2 vCPUs for builds.
     * - `BUILD_GENERAL1_MEDIUM`: Use up to 7 GB memory and 4 vCPUs for builds.
     * - `BUILD_GENERAL1_LARGE`: Use up to 16 GB memory and 8 vCPUs for builds, depending on your environment type.
     * - `BUILD_GENERAL1_2XLARGE`: Use up to 145 GB memory, 72 vCPUs, and 824 GB of SSD storage for builds. This compute
     *   type supports Docker images up to 100 GB uncompressed.
     * - `BUILD_LAMBDA_1GB`: Use up to 1 GB memory for builds. Only available for environment type `LINUX_LAMBDA_CONTAINER`
     *   and `ARM_LAMBDA_CONTAINER`.
     * - `BUILD_LAMBDA_2GB`: Use up to 2 GB memory for builds. Only available for environment type `LINUX_LAMBDA_CONTAINER`
     *   and `ARM_LAMBDA_CONTAINER`.
     * - `BUILD_LAMBDA_4GB`: Use up to 4 GB memory for builds. Only available for environment type `LINUX_LAMBDA_CONTAINER`
     *   and `ARM_LAMBDA_CONTAINER`.
     * - `BUILD_LAMBDA_8GB`: Use up to 8 GB memory for builds. Only available for environment type `LINUX_LAMBDA_CONTAINER`
     *   and `ARM_LAMBDA_CONTAINER`.
     * - `BUILD_LAMBDA_10GB`: Use up to 10 GB memory for builds. Only available for environment type
     *   `LINUX_LAMBDA_CONTAINER` and `ARM_LAMBDA_CONTAINER`.
     *
     * If you use `BUILD_GENERAL1_SMALL`:
     *
     * - For environment type `LINUX_CONTAINER`, you can use up to 3 GB memory and 2 vCPUs for builds.
     * - For environment type `LINUX_GPU_CONTAINER`, you can use up to 16 GB memory, 4 vCPUs, and 1 NVIDIA A10G Tensor Core
     *   GPU for builds.
     * - For environment type `ARM_CONTAINER`, you can use up to 4 GB memory and 2 vCPUs on ARM-based processors for builds.
     *
     * If you use `BUILD_GENERAL1_LARGE`:
     *
     * - For environment type `LINUX_CONTAINER`, you can use up to 15 GB memory and 8 vCPUs for builds.
     * - For environment type `LINUX_GPU_CONTAINER`, you can use up to 255 GB memory, 32 vCPUs, and 4 NVIDIA Tesla V100 GPUs
     *   for builds.
     * - For environment type `ARM_CONTAINER`, you can use up to 16 GB memory and 8 vCPUs on ARM-based processors for
     *   builds.
     *
     * For more information, see Build Environment Compute Types [^1] in the *CodeBuild User Guide.*
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/build-env-ref-compute-types.html
     *
     * @var ComputeType::*
     */
    private $computeType;

    /**
     * A set of environment variables to make available to builds for this build project.
     *
     * @var EnvironmentVariable[]|null
     */
    private $environmentVariables;

    /**
     * Enables running the Docker daemon inside a Docker container. Set to true only if the build project is used to build
     * Docker images. Otherwise, a build that attempts to interact with the Docker daemon fails. The default setting is
     * `false`.
     *
     * You can initialize the Docker daemon during the install phase of your build by adding one of the following sets of
     * commands to the install phase of your buildspec file:
     *
     * If the operating system's base image is Ubuntu Linux:
     *
     * `- nohup /usr/local/bin/dockerd --host=unix:///var/run/docker.sock --host=tcp://0.0.0.0:2375
     * --storage-driver=overlay&`
     *
     * `- timeout 15 sh -c "until docker info; do echo .; sleep 1; done"`
     *
     * If the operating system's base image is Alpine Linux and the previous command does not work, add the `-t` argument to
     * `timeout`:
     *
     * `- nohup /usr/local/bin/dockerd --host=unix:///var/run/docker.sock --host=tcp://0.0.0.0:2375
     * --storage-driver=overlay&`
     *
     * `- timeout -t 15 sh -c "until docker info; do echo .; sleep 1; done"`
     *
     * @var bool|null
     */
    private $privilegedMode;

    /**
     * The ARN of the Amazon S3 bucket, path prefix, and object key that contains the PEM-encoded certificate for the build
     * project. For more information, see certificate [^1] in the *CodeBuild User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/create-project-cli.html#cli.environment.certificate
     *
     * @var string|null
     */
    private $certificate;

    /**
     * The credentials for access to a private registry.
     *
     * @var RegistryCredential|null
     */
    private $registryCredential;

    /**
     * The type of credentials CodeBuild uses to pull images in your build. There are two valid values:.
     *
     * - `CODEBUILD` specifies that CodeBuild uses its own credentials. This requires that you modify your ECR repository
     *   policy to trust CodeBuild service principal.
     * - `SERVICE_ROLE` specifies that CodeBuild uses your build project's service role.
     *
     * When you use a cross-account or private registry image, you must use SERVICE_ROLE credentials. When you use an
     * CodeBuild curated image, you must use CODEBUILD credentials.
     *
     * @var ImagePullCredentialsType::*|null
     */
    private $imagePullCredentialsType;

    /**
     * @param array{
     *   type: EnvironmentType::*,
     *   image: string,
     *   computeType: ComputeType::*,
     *   environmentVariables?: null|array<EnvironmentVariable|array>,
     *   privilegedMode?: null|bool,
     *   certificate?: null|string,
     *   registryCredential?: null|RegistryCredential|array,
     *   imagePullCredentialsType?: null|ImagePullCredentialsType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->image = $input['image'] ?? $this->throwException(new InvalidArgument('Missing required field "image".'));
        $this->computeType = $input['computeType'] ?? $this->throwException(new InvalidArgument('Missing required field "computeType".'));
        $this->environmentVariables = isset($input['environmentVariables']) ? array_map([EnvironmentVariable::class, 'create'], $input['environmentVariables']) : null;
        $this->privilegedMode = $input['privilegedMode'] ?? null;
        $this->certificate = $input['certificate'] ?? null;
        $this->registryCredential = isset($input['registryCredential']) ? RegistryCredential::create($input['registryCredential']) : null;
        $this->imagePullCredentialsType = $input['imagePullCredentialsType'] ?? null;
    }

    /**
     * @param array{
     *   type: EnvironmentType::*,
     *   image: string,
     *   computeType: ComputeType::*,
     *   environmentVariables?: null|array<EnvironmentVariable|array>,
     *   privilegedMode?: null|bool,
     *   certificate?: null|string,
     *   registryCredential?: null|RegistryCredential|array,
     *   imagePullCredentialsType?: null|ImagePullCredentialsType::*,
     * }|ProjectEnvironment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    /**
     * @return ComputeType::*
     */
    public function getComputeType(): string
    {
        return $this->computeType;
    }

    /**
     * @return EnvironmentVariable[]
     */
    public function getEnvironmentVariables(): array
    {
        return $this->environmentVariables ?? [];
    }

    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return ImagePullCredentialsType::*|null
     */
    public function getImagePullCredentialsType(): ?string
    {
        return $this->imagePullCredentialsType;
    }

    public function getPrivilegedMode(): ?bool
    {
        return $this->privilegedMode;
    }

    public function getRegistryCredential(): ?RegistryCredential
    {
        return $this->registryCredential;
    }

    /**
     * @return EnvironmentType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
