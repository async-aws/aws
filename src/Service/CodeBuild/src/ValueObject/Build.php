<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\StatusType;

/**
 * Information about a build.
 */
final class Build
{
    /**
     * The unique ID for the build.
     *
     * @var string|null
     */
    private $id;

    /**
     * The Amazon Resource Name (ARN) of the build.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The number of the build. For each project, the `buildNumber` of its first build is `1`. The `buildNumber` of each
     * subsequent build is incremented by `1`. If a build is deleted, the `buildNumber` of other builds does not change.
     *
     * @var int|null
     */
    private $buildNumber;

    /**
     * When the build process started, expressed in Unix time format.
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * When the build process ended, expressed in Unix time format.
     *
     * @var \DateTimeImmutable|null
     */
    private $endTime;

    /**
     * The current build phase.
     *
     * @var string|null
     */
    private $currentPhase;

    /**
     * The current status of the build. Valid values include:
     *
     * - `FAILED`: The build failed.
     * - `FAULT`: The build faulted.
     * - `IN_PROGRESS`: The build is still in progress.
     * - `STOPPED`: The build stopped.
     * - `SUCCEEDED`: The build succeeded.
     * - `TIMED_OUT`: The build timed out.
     *
     * @var StatusType::*|null
     */
    private $buildStatus;

    /**
     * Any version identifier for the version of the source code to be built. If `sourceVersion` is specified at the project
     * level, then this `sourceVersion` (at the build level) takes precedence.
     *
     * For more information, see Source Version Sample with CodeBuild [^1] in the *CodeBuild User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/sample-source-version.html
     *
     * @var string|null
     */
    private $sourceVersion;

    /**
     * An identifier for the version of this build's source code.
     *
     * - For CodeCommit, GitHub, GitHub Enterprise, and BitBucket, the commit ID.
     * - For CodePipeline, the source revision provided by CodePipeline.
     * - For Amazon S3, this does not apply.
     *
     * @var string|null
     */
    private $resolvedSourceVersion;

    /**
     * The name of the CodeBuild project.
     *
     * @var string|null
     */
    private $projectName;

    /**
     * Information about all previous build phases that are complete and information about any current build phase that is
     * not yet complete.
     *
     * @var BuildPhase[]|null
     */
    private $phases;

    /**
     * Information about the source code to be built.
     *
     * @var ProjectSource|null
     */
    private $source;

    /**
     * An array of `ProjectSource` objects.
     *
     * @var ProjectSource[]|null
     */
    private $secondarySources;

    /**
     * An array of `ProjectSourceVersion` objects. Each `ProjectSourceVersion` must be one of:
     *
     * - For CodeCommit: the commit ID, branch, or Git tag to use.
     * - For GitHub: the commit ID, pull request ID, branch name, or tag name that corresponds to the version of the source
     *   code you want to build. If a pull request ID is specified, it must use the format `pr/pull-request-ID` (for
     *   example, `pr/25`). If a branch name is specified, the branch's HEAD commit ID is used. If not specified, the
     *   default branch's HEAD commit ID is used.
     * - For Bitbucket: the commit ID, branch name, or tag name that corresponds to the version of the source code you want
     *   to build. If a branch name is specified, the branch's HEAD commit ID is used. If not specified, the default
     *   branch's HEAD commit ID is used.
     * - For Amazon S3: the version ID of the object that represents the build input ZIP file to use.
     *
     * @var ProjectSourceVersion[]|null
     */
    private $secondarySourceVersions;

    /**
     * Information about the output artifacts for the build.
     *
     * @var BuildArtifacts|null
     */
    private $artifacts;

    /**
     * An array of `ProjectArtifacts` objects.
     *
     * @var BuildArtifacts[]|null
     */
    private $secondaryArtifacts;

    /**
     * Information about the cache for the build.
     *
     * @var ProjectCache|null
     */
    private $cache;

    /**
     * Information about the build environment for this build.
     *
     * @var ProjectEnvironment|null
     */
    private $environment;

    /**
     * The name of a service role used for this build.
     *
     * @var string|null
     */
    private $serviceRole;

    /**
     * Information about the build's logs in CloudWatch Logs.
     *
     * @var LogsLocation|null
     */
    private $logs;

    /**
     * How long, in minutes, for CodeBuild to wait before timing out this build if it does not get marked as completed.
     *
     * @var int|null
     */
    private $timeoutInMinutes;

    /**
     * The number of minutes a build is allowed to be queued before it times out.
     *
     * @var int|null
     */
    private $queuedTimeoutInMinutes;

    /**
     * Whether the build is complete. True if complete; otherwise, false.
     *
     * @var bool|null
     */
    private $buildComplete;

    /**
     * The entity that started the build. Valid values include:
     *
     * - If CodePipeline started the build, the pipeline's name (for example, `codepipeline/my-demo-pipeline`).
     * - If a user started the build, the user's name (for example, `MyUserName`).
     * - If the Jenkins plugin for CodeBuild started the build, the string `CodeBuild-Jenkins-Plugin`.
     *
     * @var string|null
     */
    private $initiator;

    /**
     * If your CodeBuild project accesses resources in an Amazon VPC, you provide this parameter that identifies the VPC ID
     * and the list of security group IDs and subnet IDs. The security groups and subnets must belong to the same VPC. You
     * must provide at least one security group and one subnet ID.
     *
     * @var VpcConfig|null
     */
    private $vpcConfig;

    /**
     * Describes a network interface.
     *
     * @var NetworkInterface|null
     */
    private $networkInterface;

    /**
     * The Key Management Service customer master key (CMK) to be used for encrypting the build output artifacts.
     *
     * > You can use a cross-account KMS key to encrypt the build output artifacts if your service role has permission to
     * > that key.
     *
     * You can specify either the Amazon Resource Name (ARN) of the CMK or, if available, the CMK's alias (using the format
     * `alias/<alias-name>`).
     *
     * @var string|null
     */
    private $encryptionKey;

    /**
     * A list of exported environment variables for this build.
     *
     * Exported environment variables are used in conjunction with CodePipeline to export environment variables from the
     * current build stage to subsequent stages in the pipeline. For more information, see Working with variables [^1] in
     * the *CodePipeline User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/codepipeline/latest/userguide/actions-variables.html
     *
     * @var ExportedEnvironmentVariable[]|null
     */
    private $exportedEnvironmentVariables;

    /**
     * An array of the ARNs associated with this build's reports.
     *
     * @var string[]|null
     */
    private $reportArns;

    /**
     * An array of `ProjectFileSystemLocation` objects for a CodeBuild build project. A `ProjectFileSystemLocation` object
     * specifies the `identifier`, `location`, `mountOptions`, `mountPoint`, and `type` of a file system created using
     * Amazon Elastic File System.
     *
     * @var ProjectFileSystemLocation[]|null
     */
    private $fileSystemLocations;

    /**
     * Contains information about the debug session for this build.
     *
     * @var DebugSession|null
     */
    private $debugSession;

    /**
     * The ARN of the batch build that this build is a member of, if applicable.
     *
     * @var string|null
     */
    private $buildBatchArn;

    /**
     * @param array{
     *   id?: null|string,
     *   arn?: null|string,
     *   buildNumber?: null|int,
     *   startTime?: null|\DateTimeImmutable,
     *   endTime?: null|\DateTimeImmutable,
     *   currentPhase?: null|string,
     *   buildStatus?: null|StatusType::*,
     *   sourceVersion?: null|string,
     *   resolvedSourceVersion?: null|string,
     *   projectName?: null|string,
     *   phases?: null|array<BuildPhase|array>,
     *   source?: null|ProjectSource|array,
     *   secondarySources?: null|array<ProjectSource|array>,
     *   secondarySourceVersions?: null|array<ProjectSourceVersion|array>,
     *   artifacts?: null|BuildArtifacts|array,
     *   secondaryArtifacts?: null|array<BuildArtifacts|array>,
     *   cache?: null|ProjectCache|array,
     *   environment?: null|ProjectEnvironment|array,
     *   serviceRole?: null|string,
     *   logs?: null|LogsLocation|array,
     *   timeoutInMinutes?: null|int,
     *   queuedTimeoutInMinutes?: null|int,
     *   buildComplete?: null|bool,
     *   initiator?: null|string,
     *   vpcConfig?: null|VpcConfig|array,
     *   networkInterface?: null|NetworkInterface|array,
     *   encryptionKey?: null|string,
     *   exportedEnvironmentVariables?: null|array<ExportedEnvironmentVariable|array>,
     *   reportArns?: null|string[],
     *   fileSystemLocations?: null|array<ProjectFileSystemLocation|array>,
     *   debugSession?: null|DebugSession|array,
     *   buildBatchArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['id'] ?? null;
        $this->arn = $input['arn'] ?? null;
        $this->buildNumber = $input['buildNumber'] ?? null;
        $this->startTime = $input['startTime'] ?? null;
        $this->endTime = $input['endTime'] ?? null;
        $this->currentPhase = $input['currentPhase'] ?? null;
        $this->buildStatus = $input['buildStatus'] ?? null;
        $this->sourceVersion = $input['sourceVersion'] ?? null;
        $this->resolvedSourceVersion = $input['resolvedSourceVersion'] ?? null;
        $this->projectName = $input['projectName'] ?? null;
        $this->phases = isset($input['phases']) ? array_map([BuildPhase::class, 'create'], $input['phases']) : null;
        $this->source = isset($input['source']) ? ProjectSource::create($input['source']) : null;
        $this->secondarySources = isset($input['secondarySources']) ? array_map([ProjectSource::class, 'create'], $input['secondarySources']) : null;
        $this->secondarySourceVersions = isset($input['secondarySourceVersions']) ? array_map([ProjectSourceVersion::class, 'create'], $input['secondarySourceVersions']) : null;
        $this->artifacts = isset($input['artifacts']) ? BuildArtifacts::create($input['artifacts']) : null;
        $this->secondaryArtifacts = isset($input['secondaryArtifacts']) ? array_map([BuildArtifacts::class, 'create'], $input['secondaryArtifacts']) : null;
        $this->cache = isset($input['cache']) ? ProjectCache::create($input['cache']) : null;
        $this->environment = isset($input['environment']) ? ProjectEnvironment::create($input['environment']) : null;
        $this->serviceRole = $input['serviceRole'] ?? null;
        $this->logs = isset($input['logs']) ? LogsLocation::create($input['logs']) : null;
        $this->timeoutInMinutes = $input['timeoutInMinutes'] ?? null;
        $this->queuedTimeoutInMinutes = $input['queuedTimeoutInMinutes'] ?? null;
        $this->buildComplete = $input['buildComplete'] ?? null;
        $this->initiator = $input['initiator'] ?? null;
        $this->vpcConfig = isset($input['vpcConfig']) ? VpcConfig::create($input['vpcConfig']) : null;
        $this->networkInterface = isset($input['networkInterface']) ? NetworkInterface::create($input['networkInterface']) : null;
        $this->encryptionKey = $input['encryptionKey'] ?? null;
        $this->exportedEnvironmentVariables = isset($input['exportedEnvironmentVariables']) ? array_map([ExportedEnvironmentVariable::class, 'create'], $input['exportedEnvironmentVariables']) : null;
        $this->reportArns = $input['reportArns'] ?? null;
        $this->fileSystemLocations = isset($input['fileSystemLocations']) ? array_map([ProjectFileSystemLocation::class, 'create'], $input['fileSystemLocations']) : null;
        $this->debugSession = isset($input['debugSession']) ? DebugSession::create($input['debugSession']) : null;
        $this->buildBatchArn = $input['buildBatchArn'] ?? null;
    }

    /**
     * @param array{
     *   id?: null|string,
     *   arn?: null|string,
     *   buildNumber?: null|int,
     *   startTime?: null|\DateTimeImmutable,
     *   endTime?: null|\DateTimeImmutable,
     *   currentPhase?: null|string,
     *   buildStatus?: null|StatusType::*,
     *   sourceVersion?: null|string,
     *   resolvedSourceVersion?: null|string,
     *   projectName?: null|string,
     *   phases?: null|array<BuildPhase|array>,
     *   source?: null|ProjectSource|array,
     *   secondarySources?: null|array<ProjectSource|array>,
     *   secondarySourceVersions?: null|array<ProjectSourceVersion|array>,
     *   artifacts?: null|BuildArtifacts|array,
     *   secondaryArtifacts?: null|array<BuildArtifacts|array>,
     *   cache?: null|ProjectCache|array,
     *   environment?: null|ProjectEnvironment|array,
     *   serviceRole?: null|string,
     *   logs?: null|LogsLocation|array,
     *   timeoutInMinutes?: null|int,
     *   queuedTimeoutInMinutes?: null|int,
     *   buildComplete?: null|bool,
     *   initiator?: null|string,
     *   vpcConfig?: null|VpcConfig|array,
     *   networkInterface?: null|NetworkInterface|array,
     *   encryptionKey?: null|string,
     *   exportedEnvironmentVariables?: null|array<ExportedEnvironmentVariable|array>,
     *   reportArns?: null|string[],
     *   fileSystemLocations?: null|array<ProjectFileSystemLocation|array>,
     *   debugSession?: null|DebugSession|array,
     *   buildBatchArn?: null|string,
     * }|Build $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getArtifacts(): ?BuildArtifacts
    {
        return $this->artifacts;
    }

    public function getBuildBatchArn(): ?string
    {
        return $this->buildBatchArn;
    }

    public function getBuildComplete(): ?bool
    {
        return $this->buildComplete;
    }

    public function getBuildNumber(): ?int
    {
        return $this->buildNumber;
    }

    /**
     * @return StatusType::*|null
     */
    public function getBuildStatus(): ?string
    {
        return $this->buildStatus;
    }

    public function getCache(): ?ProjectCache
    {
        return $this->cache;
    }

    public function getCurrentPhase(): ?string
    {
        return $this->currentPhase;
    }

    public function getDebugSession(): ?DebugSession
    {
        return $this->debugSession;
    }

    public function getEncryptionKey(): ?string
    {
        return $this->encryptionKey;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function getEnvironment(): ?ProjectEnvironment
    {
        return $this->environment;
    }

    /**
     * @return ExportedEnvironmentVariable[]
     */
    public function getExportedEnvironmentVariables(): array
    {
        return $this->exportedEnvironmentVariables ?? [];
    }

    /**
     * @return ProjectFileSystemLocation[]
     */
    public function getFileSystemLocations(): array
    {
        return $this->fileSystemLocations ?? [];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getInitiator(): ?string
    {
        return $this->initiator;
    }

    public function getLogs(): ?LogsLocation
    {
        return $this->logs;
    }

    public function getNetworkInterface(): ?NetworkInterface
    {
        return $this->networkInterface;
    }

    /**
     * @return BuildPhase[]
     */
    public function getPhases(): array
    {
        return $this->phases ?? [];
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function getQueuedTimeoutInMinutes(): ?int
    {
        return $this->queuedTimeoutInMinutes;
    }

    /**
     * @return string[]
     */
    public function getReportArns(): array
    {
        return $this->reportArns ?? [];
    }

    public function getResolvedSourceVersion(): ?string
    {
        return $this->resolvedSourceVersion;
    }

    /**
     * @return BuildArtifacts[]
     */
    public function getSecondaryArtifacts(): array
    {
        return $this->secondaryArtifacts ?? [];
    }

    /**
     * @return ProjectSourceVersion[]
     */
    public function getSecondarySourceVersions(): array
    {
        return $this->secondarySourceVersions ?? [];
    }

    /**
     * @return ProjectSource[]
     */
    public function getSecondarySources(): array
    {
        return $this->secondarySources ?? [];
    }

    public function getServiceRole(): ?string
    {
        return $this->serviceRole;
    }

    public function getSource(): ?ProjectSource
    {
        return $this->source;
    }

    public function getSourceVersion(): ?string
    {
        return $this->sourceVersion;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getTimeoutInMinutes(): ?int
    {
        return $this->timeoutInMinutes;
    }

    public function getVpcConfig(): ?VpcConfig
    {
        return $this->vpcConfig;
    }
}
