<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\ComputeType;
use AsyncAws\CodeBuild\Enum\EnvironmentType;
use AsyncAws\CodeBuild\Enum\ImagePullCredentialsType;

/**
 * Information about the build environment for this build.
 */
final class ProjectEnvironment
{
    /**
     * The type of build environment to use for related builds.
     */
    private $type;

    /**
     * The image tag or image digest that identifies the Docker image to use for this build project. Use the following
     * formats:.
     */
    private $image;

    /**
     * Information about the compute resources the build project uses. Available values include:.
     */
    private $computeType;

    /**
     * A set of environment variables to make available to builds for this build project.
     */
    private $environmentVariables;

    /**
     * Enables running the Docker daemon inside a Docker container. Set to true only if the build project is used to build
     * Docker images. Otherwise, a build that attempts to interact with the Docker daemon fails. The default setting is
     * `false`.
     */
    private $privilegedMode;

    /**
     * The ARN of the Amazon S3 bucket, path prefix, and object key that contains the PEM-encoded certificate for the build
     * project. For more information, see certificate in the *CodeBuild User Guide*.
     *
     * @see https://docs.aws.amazon.com/codebuild/latest/userguide/create-project-cli.html#cli.environment.certificate
     */
    private $certificate;

    /**
     * The credentials for access to a private registry.
     */
    private $registryCredential;

    /**
     * The type of credentials CodeBuild uses to pull images in your build. There are two valid values:.
     */
    private $imagePullCredentialsType;

    /**
     * @param array{
     *   type: EnvironmentType::*,
     *   image: string,
     *   computeType: ComputeType::*,
     *   environmentVariables?: null|EnvironmentVariable[],
     *   privilegedMode?: null|bool,
     *   certificate?: null|string,
     *   registryCredential?: null|RegistryCredential|array,
     *   imagePullCredentialsType?: null|ImagePullCredentialsType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? null;
        $this->image = $input['image'] ?? null;
        $this->computeType = $input['computeType'] ?? null;
        $this->environmentVariables = isset($input['environmentVariables']) ? array_map([EnvironmentVariable::class, 'create'], $input['environmentVariables']) : null;
        $this->privilegedMode = $input['privilegedMode'] ?? null;
        $this->certificate = $input['certificate'] ?? null;
        $this->registryCredential = isset($input['registryCredential']) ? RegistryCredential::create($input['registryCredential']) : null;
        $this->imagePullCredentialsType = $input['imagePullCredentialsType'] ?? null;
    }

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
}
