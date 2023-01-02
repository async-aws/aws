<?php

namespace AsyncAws\CodeBuild\Input;

use AsyncAws\CodeBuild\Enum\ComputeType;
use AsyncAws\CodeBuild\Enum\EnvironmentType;
use AsyncAws\CodeBuild\Enum\ImagePullCredentialsType;
use AsyncAws\CodeBuild\Enum\SourceType;
use AsyncAws\CodeBuild\ValueObject\BuildStatusConfig;
use AsyncAws\CodeBuild\ValueObject\EnvironmentVariable;
use AsyncAws\CodeBuild\ValueObject\GitSubmodulesConfig;
use AsyncAws\CodeBuild\ValueObject\LogsConfig;
use AsyncAws\CodeBuild\ValueObject\ProjectArtifacts;
use AsyncAws\CodeBuild\ValueObject\ProjectCache;
use AsyncAws\CodeBuild\ValueObject\ProjectSource;
use AsyncAws\CodeBuild\ValueObject\ProjectSourceVersion;
use AsyncAws\CodeBuild\ValueObject\RegistryCredential;
use AsyncAws\CodeBuild\ValueObject\SourceAuth;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartBuildInput extends Input
{
    /**
     * The name of the CodeBuild build project to start running a build.
     *
     * @required
     *
     * @var string|null
     */
    private $projectName;

    /**
     * An array of `ProjectSource` objects.
     *
     * @var ProjectSource[]|null
     */
    private $secondarySourcesOverride;

    /**
     * An array of `ProjectSourceVersion` objects that specify one or more versions of the project's secondary sources to be
     * used for this build only.
     *
     * @var ProjectSourceVersion[]|null
     */
    private $secondarySourcesVersionOverride;

    /**
     * The version of the build input to be built, for this build only. If not specified, the latest version is used. If
     * specified, the contents depends on the source provider:.
     *
     * @var string|null
     */
    private $sourceVersion;

    /**
     * Build output artifact settings that override, for this build only, the latest ones already defined in the build
     * project.
     *
     * @var ProjectArtifacts|null
     */
    private $artifactsOverride;

    /**
     * An array of `ProjectArtifacts` objects.
     *
     * @var ProjectArtifacts[]|null
     */
    private $secondaryArtifactsOverride;

    /**
     * A set of environment variables that overrides, for this build only, the latest ones already defined in the build
     * project.
     *
     * @var EnvironmentVariable[]|null
     */
    private $environmentVariablesOverride;

    /**
     * A source input type, for this build, that overrides the source input defined in the build project.
     *
     * @var SourceType::*|null
     */
    private $sourceTypeOverride;

    /**
     * A location that overrides, for this build, the source location for the one defined in the build project.
     *
     * @var string|null
     */
    private $sourceLocationOverride;

    /**
     * An authorization type for this build that overrides the one defined in the build project. This override applies only
     * if the build project's source is BitBucket or GitHub.
     *
     * @var SourceAuth|null
     */
    private $sourceAuthOverride;

    /**
     * The user-defined depth of history, with a minimum value of 0, that overrides, for this build only, any previous depth
     * of history defined in the build project.
     *
     * @var int|null
     */
    private $gitCloneDepthOverride;

    /**
     * Information about the Git submodules configuration for this build of an CodeBuild build project.
     *
     * @var GitSubmodulesConfig|null
     */
    private $gitSubmodulesConfigOverride;

    /**
     * A buildspec file declaration that overrides, for this build only, the latest one already defined in the build
     * project.
     *
     * @var string|null
     */
    private $buildspecOverride;

    /**
     * Enable this flag to override the insecure SSL setting that is specified in the build project. The insecure SSL
     * setting determines whether to ignore SSL warnings while connecting to the project source code. This override applies
     * only if the build's source is GitHub Enterprise.
     *
     * @var bool|null
     */
    private $insecureSslOverride;

    /**
     * Set to true to report to your source provider the status of a build's start and completion. If you use this option
     * with a source provider other than GitHub, GitHub Enterprise, or Bitbucket, an `invalidInputException` is thrown.
     *
     * @var bool|null
     */
    private $reportBuildStatusOverride;

    /**
     * Contains information that defines how the build project reports the build status to the source provider. This option
     * is only used when the source provider is `GITHUB`, `GITHUB_ENTERPRISE`, or `BITBUCKET`.
     *
     * @var BuildStatusConfig|null
     */
    private $buildStatusConfigOverride;

    /**
     * A container type for this build that overrides the one specified in the build project.
     *
     * @var EnvironmentType::*|null
     */
    private $environmentTypeOverride;

    /**
     * The name of an image for this build that overrides the one specified in the build project.
     *
     * @var string|null
     */
    private $imageOverride;

    /**
     * The name of a compute type for this build that overrides the one specified in the build project.
     *
     * @var ComputeType::*|null
     */
    private $computeTypeOverride;

    /**
     * The name of a certificate for this build that overrides the one specified in the build project.
     *
     * @var string|null
     */
    private $certificateOverride;

    /**
     * A ProjectCache object specified for this build that overrides the one defined in the build project.
     *
     * @var ProjectCache|null
     */
    private $cacheOverride;

    /**
     * The name of a service role for this build that overrides the one specified in the build project.
     *
     * @var string|null
     */
    private $serviceRoleOverride;

    /**
     * Enable this flag to override privileged mode in the build project.
     *
     * @var bool|null
     */
    private $privilegedModeOverride;

    /**
     * The number of build timeout minutes, from 5 to 480 (8 hours), that overrides, for this build only, the latest setting
     * already defined in the build project.
     *
     * @var int|null
     */
    private $timeoutInMinutesOverride;

    /**
     * The number of minutes a build is allowed to be queued before it times out.
     *
     * @var int|null
     */
    private $queuedTimeoutInMinutesOverride;

    /**
     * The Key Management Service customer master key (CMK) that overrides the one specified in the build project. The CMK
     * key encrypts the build output artifacts.
     *
     * @var string|null
     */
    private $encryptionKeyOverride;

    /**
     * A unique, case sensitive identifier you provide to ensure the idempotency of the StartBuild request. The token is
     * included in the StartBuild request and is valid for 5 minutes. If you repeat the StartBuild request with the same
     * token, but change a parameter, CodeBuild returns a parameter mismatch error.
     *
     * @var string|null
     */
    private $idempotencyToken;

    /**
     * Log settings for this build that override the log settings defined in the build project.
     *
     * @var LogsConfig|null
     */
    private $logsConfigOverride;

    /**
     * The credentials for access to a private registry.
     *
     * @var RegistryCredential|null
     */
    private $registryCredentialOverride;

    /**
     * The type of credentials CodeBuild uses to pull images in your build. There are two valid values:.
     *
     * @var ImagePullCredentialsType::*|null
     */
    private $imagePullCredentialsTypeOverride;

    /**
     * Specifies if session debugging is enabled for this build. For more information, see Viewing a running build in
     * Session Manager.
     *
     * @see https://docs.aws.amazon.com/codebuild/latest/userguide/session-manager.html
     *
     * @var bool|null
     */
    private $debugSessionEnabled;

    /**
     * @param array{
     *   projectName?: string,
     *   secondarySourcesOverride?: ProjectSource[],
     *   secondarySourcesVersionOverride?: ProjectSourceVersion[],
     *   sourceVersion?: string,
     *   artifactsOverride?: ProjectArtifacts|array,
     *   secondaryArtifactsOverride?: ProjectArtifacts[],
     *   environmentVariablesOverride?: EnvironmentVariable[],
     *   sourceTypeOverride?: SourceType::*,
     *   sourceLocationOverride?: string,
     *   sourceAuthOverride?: SourceAuth|array,
     *   gitCloneDepthOverride?: int,
     *   gitSubmodulesConfigOverride?: GitSubmodulesConfig|array,
     *   buildspecOverride?: string,
     *   insecureSslOverride?: bool,
     *   reportBuildStatusOverride?: bool,
     *   buildStatusConfigOverride?: BuildStatusConfig|array,
     *   environmentTypeOverride?: EnvironmentType::*,
     *   imageOverride?: string,
     *   computeTypeOverride?: ComputeType::*,
     *   certificateOverride?: string,
     *   cacheOverride?: ProjectCache|array,
     *   serviceRoleOverride?: string,
     *   privilegedModeOverride?: bool,
     *   timeoutInMinutesOverride?: int,
     *   queuedTimeoutInMinutesOverride?: int,
     *   encryptionKeyOverride?: string,
     *   idempotencyToken?: string,
     *   logsConfigOverride?: LogsConfig|array,
     *   registryCredentialOverride?: RegistryCredential|array,
     *   imagePullCredentialsTypeOverride?: ImagePullCredentialsType::*,
     *   debugSessionEnabled?: bool,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->projectName = $input['projectName'] ?? null;
        $this->secondarySourcesOverride = isset($input['secondarySourcesOverride']) ? array_map([ProjectSource::class, 'create'], $input['secondarySourcesOverride']) : null;
        $this->secondarySourcesVersionOverride = isset($input['secondarySourcesVersionOverride']) ? array_map([ProjectSourceVersion::class, 'create'], $input['secondarySourcesVersionOverride']) : null;
        $this->sourceVersion = $input['sourceVersion'] ?? null;
        $this->artifactsOverride = isset($input['artifactsOverride']) ? ProjectArtifacts::create($input['artifactsOverride']) : null;
        $this->secondaryArtifactsOverride = isset($input['secondaryArtifactsOverride']) ? array_map([ProjectArtifacts::class, 'create'], $input['secondaryArtifactsOverride']) : null;
        $this->environmentVariablesOverride = isset($input['environmentVariablesOverride']) ? array_map([EnvironmentVariable::class, 'create'], $input['environmentVariablesOverride']) : null;
        $this->sourceTypeOverride = $input['sourceTypeOverride'] ?? null;
        $this->sourceLocationOverride = $input['sourceLocationOverride'] ?? null;
        $this->sourceAuthOverride = isset($input['sourceAuthOverride']) ? SourceAuth::create($input['sourceAuthOverride']) : null;
        $this->gitCloneDepthOverride = $input['gitCloneDepthOverride'] ?? null;
        $this->gitSubmodulesConfigOverride = isset($input['gitSubmodulesConfigOverride']) ? GitSubmodulesConfig::create($input['gitSubmodulesConfigOverride']) : null;
        $this->buildspecOverride = $input['buildspecOverride'] ?? null;
        $this->insecureSslOverride = $input['insecureSslOverride'] ?? null;
        $this->reportBuildStatusOverride = $input['reportBuildStatusOverride'] ?? null;
        $this->buildStatusConfigOverride = isset($input['buildStatusConfigOverride']) ? BuildStatusConfig::create($input['buildStatusConfigOverride']) : null;
        $this->environmentTypeOverride = $input['environmentTypeOverride'] ?? null;
        $this->imageOverride = $input['imageOverride'] ?? null;
        $this->computeTypeOverride = $input['computeTypeOverride'] ?? null;
        $this->certificateOverride = $input['certificateOverride'] ?? null;
        $this->cacheOverride = isset($input['cacheOverride']) ? ProjectCache::create($input['cacheOverride']) : null;
        $this->serviceRoleOverride = $input['serviceRoleOverride'] ?? null;
        $this->privilegedModeOverride = $input['privilegedModeOverride'] ?? null;
        $this->timeoutInMinutesOverride = $input['timeoutInMinutesOverride'] ?? null;
        $this->queuedTimeoutInMinutesOverride = $input['queuedTimeoutInMinutesOverride'] ?? null;
        $this->encryptionKeyOverride = $input['encryptionKeyOverride'] ?? null;
        $this->idempotencyToken = $input['idempotencyToken'] ?? null;
        $this->logsConfigOverride = isset($input['logsConfigOverride']) ? LogsConfig::create($input['logsConfigOverride']) : null;
        $this->registryCredentialOverride = isset($input['registryCredentialOverride']) ? RegistryCredential::create($input['registryCredentialOverride']) : null;
        $this->imagePullCredentialsTypeOverride = $input['imagePullCredentialsTypeOverride'] ?? null;
        $this->debugSessionEnabled = $input['debugSessionEnabled'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArtifactsOverride(): ?ProjectArtifacts
    {
        return $this->artifactsOverride;
    }

    public function getBuildStatusConfigOverride(): ?BuildStatusConfig
    {
        return $this->buildStatusConfigOverride;
    }

    public function getBuildspecOverride(): ?string
    {
        return $this->buildspecOverride;
    }

    public function getCacheOverride(): ?ProjectCache
    {
        return $this->cacheOverride;
    }

    public function getCertificateOverride(): ?string
    {
        return $this->certificateOverride;
    }

    /**
     * @return ComputeType::*|null
     */
    public function getComputeTypeOverride(): ?string
    {
        return $this->computeTypeOverride;
    }

    public function getDebugSessionEnabled(): ?bool
    {
        return $this->debugSessionEnabled;
    }

    public function getEncryptionKeyOverride(): ?string
    {
        return $this->encryptionKeyOverride;
    }

    /**
     * @return EnvironmentType::*|null
     */
    public function getEnvironmentTypeOverride(): ?string
    {
        return $this->environmentTypeOverride;
    }

    /**
     * @return EnvironmentVariable[]
     */
    public function getEnvironmentVariablesOverride(): array
    {
        return $this->environmentVariablesOverride ?? [];
    }

    public function getGitCloneDepthOverride(): ?int
    {
        return $this->gitCloneDepthOverride;
    }

    public function getGitSubmodulesConfigOverride(): ?GitSubmodulesConfig
    {
        return $this->gitSubmodulesConfigOverride;
    }

    public function getIdempotencyToken(): ?string
    {
        return $this->idempotencyToken;
    }

    public function getImageOverride(): ?string
    {
        return $this->imageOverride;
    }

    /**
     * @return ImagePullCredentialsType::*|null
     */
    public function getImagePullCredentialsTypeOverride(): ?string
    {
        return $this->imagePullCredentialsTypeOverride;
    }

    public function getInsecureSslOverride(): ?bool
    {
        return $this->insecureSslOverride;
    }

    public function getLogsConfigOverride(): ?LogsConfig
    {
        return $this->logsConfigOverride;
    }

    public function getPrivilegedModeOverride(): ?bool
    {
        return $this->privilegedModeOverride;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function getQueuedTimeoutInMinutesOverride(): ?int
    {
        return $this->queuedTimeoutInMinutesOverride;
    }

    public function getRegistryCredentialOverride(): ?RegistryCredential
    {
        return $this->registryCredentialOverride;
    }

    public function getReportBuildStatusOverride(): ?bool
    {
        return $this->reportBuildStatusOverride;
    }

    /**
     * @return ProjectArtifacts[]
     */
    public function getSecondaryArtifactsOverride(): array
    {
        return $this->secondaryArtifactsOverride ?? [];
    }

    /**
     * @return ProjectSource[]
     */
    public function getSecondarySourcesOverride(): array
    {
        return $this->secondarySourcesOverride ?? [];
    }

    /**
     * @return ProjectSourceVersion[]
     */
    public function getSecondarySourcesVersionOverride(): array
    {
        return $this->secondarySourcesVersionOverride ?? [];
    }

    public function getServiceRoleOverride(): ?string
    {
        return $this->serviceRoleOverride;
    }

    public function getSourceAuthOverride(): ?SourceAuth
    {
        return $this->sourceAuthOverride;
    }

    public function getSourceLocationOverride(): ?string
    {
        return $this->sourceLocationOverride;
    }

    /**
     * @return SourceType::*|null
     */
    public function getSourceTypeOverride(): ?string
    {
        return $this->sourceTypeOverride;
    }

    public function getSourceVersion(): ?string
    {
        return $this->sourceVersion;
    }

    public function getTimeoutInMinutesOverride(): ?int
    {
        return $this->timeoutInMinutesOverride;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeBuild_20161006.StartBuild',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setArtifactsOverride(?ProjectArtifacts $value): self
    {
        $this->artifactsOverride = $value;

        return $this;
    }

    public function setBuildStatusConfigOverride(?BuildStatusConfig $value): self
    {
        $this->buildStatusConfigOverride = $value;

        return $this;
    }

    public function setBuildspecOverride(?string $value): self
    {
        $this->buildspecOverride = $value;

        return $this;
    }

    public function setCacheOverride(?ProjectCache $value): self
    {
        $this->cacheOverride = $value;

        return $this;
    }

    public function setCertificateOverride(?string $value): self
    {
        $this->certificateOverride = $value;

        return $this;
    }

    /**
     * @param ComputeType::*|null $value
     */
    public function setComputeTypeOverride(?string $value): self
    {
        $this->computeTypeOverride = $value;

        return $this;
    }

    public function setDebugSessionEnabled(?bool $value): self
    {
        $this->debugSessionEnabled = $value;

        return $this;
    }

    public function setEncryptionKeyOverride(?string $value): self
    {
        $this->encryptionKeyOverride = $value;

        return $this;
    }

    /**
     * @param EnvironmentType::*|null $value
     */
    public function setEnvironmentTypeOverride(?string $value): self
    {
        $this->environmentTypeOverride = $value;

        return $this;
    }

    /**
     * @param EnvironmentVariable[] $value
     */
    public function setEnvironmentVariablesOverride(array $value): self
    {
        $this->environmentVariablesOverride = $value;

        return $this;
    }

    public function setGitCloneDepthOverride(?int $value): self
    {
        $this->gitCloneDepthOverride = $value;

        return $this;
    }

    public function setGitSubmodulesConfigOverride(?GitSubmodulesConfig $value): self
    {
        $this->gitSubmodulesConfigOverride = $value;

        return $this;
    }

    public function setIdempotencyToken(?string $value): self
    {
        $this->idempotencyToken = $value;

        return $this;
    }

    public function setImageOverride(?string $value): self
    {
        $this->imageOverride = $value;

        return $this;
    }

    /**
     * @param ImagePullCredentialsType::*|null $value
     */
    public function setImagePullCredentialsTypeOverride(?string $value): self
    {
        $this->imagePullCredentialsTypeOverride = $value;

        return $this;
    }

    public function setInsecureSslOverride(?bool $value): self
    {
        $this->insecureSslOverride = $value;

        return $this;
    }

    public function setLogsConfigOverride(?LogsConfig $value): self
    {
        $this->logsConfigOverride = $value;

        return $this;
    }

    public function setPrivilegedModeOverride(?bool $value): self
    {
        $this->privilegedModeOverride = $value;

        return $this;
    }

    public function setProjectName(?string $value): self
    {
        $this->projectName = $value;

        return $this;
    }

    public function setQueuedTimeoutInMinutesOverride(?int $value): self
    {
        $this->queuedTimeoutInMinutesOverride = $value;

        return $this;
    }

    public function setRegistryCredentialOverride(?RegistryCredential $value): self
    {
        $this->registryCredentialOverride = $value;

        return $this;
    }

    public function setReportBuildStatusOverride(?bool $value): self
    {
        $this->reportBuildStatusOverride = $value;

        return $this;
    }

    /**
     * @param ProjectArtifacts[] $value
     */
    public function setSecondaryArtifactsOverride(array $value): self
    {
        $this->secondaryArtifactsOverride = $value;

        return $this;
    }

    /**
     * @param ProjectSource[] $value
     */
    public function setSecondarySourcesOverride(array $value): self
    {
        $this->secondarySourcesOverride = $value;

        return $this;
    }

    /**
     * @param ProjectSourceVersion[] $value
     */
    public function setSecondarySourcesVersionOverride(array $value): self
    {
        $this->secondarySourcesVersionOverride = $value;

        return $this;
    }

    public function setServiceRoleOverride(?string $value): self
    {
        $this->serviceRoleOverride = $value;

        return $this;
    }

    public function setSourceAuthOverride(?SourceAuth $value): self
    {
        $this->sourceAuthOverride = $value;

        return $this;
    }

    public function setSourceLocationOverride(?string $value): self
    {
        $this->sourceLocationOverride = $value;

        return $this;
    }

    /**
     * @param SourceType::*|null $value
     */
    public function setSourceTypeOverride(?string $value): self
    {
        $this->sourceTypeOverride = $value;

        return $this;
    }

    public function setSourceVersion(?string $value): self
    {
        $this->sourceVersion = $value;

        return $this;
    }

    public function setTimeoutInMinutesOverride(?int $value): self
    {
        $this->timeoutInMinutesOverride = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->projectName) {
            throw new InvalidArgument(sprintf('Missing parameter "projectName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['projectName'] = $v;
        if (null !== $v = $this->secondarySourcesOverride) {
            $index = -1;
            $payload['secondarySourcesOverride'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['secondarySourcesOverride'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->secondarySourcesVersionOverride) {
            $index = -1;
            $payload['secondarySourcesVersionOverride'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['secondarySourcesVersionOverride'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->sourceVersion) {
            $payload['sourceVersion'] = $v;
        }
        if (null !== $v = $this->artifactsOverride) {
            $payload['artifactsOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->secondaryArtifactsOverride) {
            $index = -1;
            $payload['secondaryArtifactsOverride'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['secondaryArtifactsOverride'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->environmentVariablesOverride) {
            $index = -1;
            $payload['environmentVariablesOverride'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['environmentVariablesOverride'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->sourceTypeOverride) {
            if (!SourceType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "sourceTypeOverride" for "%s". The value "%s" is not a valid "SourceType".', __CLASS__, $v));
            }
            $payload['sourceTypeOverride'] = $v;
        }
        if (null !== $v = $this->sourceLocationOverride) {
            $payload['sourceLocationOverride'] = $v;
        }
        if (null !== $v = $this->sourceAuthOverride) {
            $payload['sourceAuthOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->gitCloneDepthOverride) {
            $payload['gitCloneDepthOverride'] = $v;
        }
        if (null !== $v = $this->gitSubmodulesConfigOverride) {
            $payload['gitSubmodulesConfigOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->buildspecOverride) {
            $payload['buildspecOverride'] = $v;
        }
        if (null !== $v = $this->insecureSslOverride) {
            $payload['insecureSslOverride'] = (bool) $v;
        }
        if (null !== $v = $this->reportBuildStatusOverride) {
            $payload['reportBuildStatusOverride'] = (bool) $v;
        }
        if (null !== $v = $this->buildStatusConfigOverride) {
            $payload['buildStatusConfigOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->environmentTypeOverride) {
            if (!EnvironmentType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "environmentTypeOverride" for "%s". The value "%s" is not a valid "EnvironmentType".', __CLASS__, $v));
            }
            $payload['environmentTypeOverride'] = $v;
        }
        if (null !== $v = $this->imageOverride) {
            $payload['imageOverride'] = $v;
        }
        if (null !== $v = $this->computeTypeOverride) {
            if (!ComputeType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "computeTypeOverride" for "%s". The value "%s" is not a valid "ComputeType".', __CLASS__, $v));
            }
            $payload['computeTypeOverride'] = $v;
        }
        if (null !== $v = $this->certificateOverride) {
            $payload['certificateOverride'] = $v;
        }
        if (null !== $v = $this->cacheOverride) {
            $payload['cacheOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->serviceRoleOverride) {
            $payload['serviceRoleOverride'] = $v;
        }
        if (null !== $v = $this->privilegedModeOverride) {
            $payload['privilegedModeOverride'] = (bool) $v;
        }
        if (null !== $v = $this->timeoutInMinutesOverride) {
            $payload['timeoutInMinutesOverride'] = $v;
        }
        if (null !== $v = $this->queuedTimeoutInMinutesOverride) {
            $payload['queuedTimeoutInMinutesOverride'] = $v;
        }
        if (null !== $v = $this->encryptionKeyOverride) {
            $payload['encryptionKeyOverride'] = $v;
        }
        if (null !== $v = $this->idempotencyToken) {
            $payload['idempotencyToken'] = $v;
        }
        if (null !== $v = $this->logsConfigOverride) {
            $payload['logsConfigOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->registryCredentialOverride) {
            $payload['registryCredentialOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->imagePullCredentialsTypeOverride) {
            if (!ImagePullCredentialsType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "imagePullCredentialsTypeOverride" for "%s". The value "%s" is not a valid "ImagePullCredentialsType".', __CLASS__, $v));
            }
            $payload['imagePullCredentialsTypeOverride'] = $v;
        }
        if (null !== $v = $this->debugSessionEnabled) {
            $payload['debugSessionEnabled'] = (bool) $v;
        }

        return $payload;
    }
}
