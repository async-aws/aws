<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\LastUpdateStatus;
use AsyncAws\Lambda\Enum\LastUpdateStatusReasonCode;
use AsyncAws\Lambda\Enum\PackageType;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Enum\State;
use AsyncAws\Lambda\Enum\StateReasonCode;

/**
 * Details about a function's configuration.
 */
final class FunctionConfiguration
{
    /**
     * The name of the function.
     *
     * @var string|null
     */
    private $functionName;

    /**
     * The function's Amazon Resource Name (ARN).
     *
     * @var string|null
     */
    private $functionArn;

    /**
     * The identifier of the function's runtime [^1]. Runtime is required if the deployment package is a .zip file archive.
     *
     * The following list includes deprecated runtimes. For more information, see Runtime deprecation policy [^2].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html#runtime-support-policy
     *
     * @var Runtime::*|null
     */
    private $runtime;

    /**
     * The function's execution role.
     *
     * @var string|null
     */
    private $role;

    /**
     * The function that Lambda calls to begin running your function.
     *
     * @var string|null
     */
    private $handler;

    /**
     * The size of the function's deployment package, in bytes.
     *
     * @var int|null
     */
    private $codeSize;

    /**
     * The function's description.
     *
     * @var string|null
     */
    private $description;

    /**
     * The amount of time in seconds that Lambda allows a function to run before stopping it.
     *
     * @var int|null
     */
    private $timeout;

    /**
     * The amount of memory available to the function at runtime.
     *
     * @var int|null
     */
    private $memorySize;

    /**
     * The date and time that the function was last updated, in ISO-8601 format [^1] (YYYY-MM-DDThh:mm:ss.sTZD).
     *
     * [^1]: https://www.w3.org/TR/NOTE-datetime
     *
     * @var string|null
     */
    private $lastModified;

    /**
     * The SHA256 hash of the function's deployment package.
     *
     * @var string|null
     */
    private $codeSha256;

    /**
     * The version of the Lambda function.
     *
     * @var string|null
     */
    private $version;

    /**
     * The function's networking configuration.
     *
     * @var VpcConfigResponse|null
     */
    private $vpcConfig;

    /**
     * The function's dead letter queue.
     *
     * @var DeadLetterConfig|null
     */
    private $deadLetterConfig;

    /**
     * The function's environment variables [^1]. Omitted from CloudTrail logs.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-envvars.html
     *
     * @var EnvironmentResponse|null
     */
    private $environment;

    /**
     * The KMS key that's used to encrypt the function's environment variables [^1]. When Lambda SnapStart [^2] is
     * activated, this key is also used to encrypt the function's snapshot. This key is returned only if you've configured a
     * customer managed key.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-envvars.html#configuration-envvars-encryption
     * [^2]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart-security.html
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * The function's X-Ray tracing configuration.
     *
     * @var TracingConfigResponse|null
     */
    private $tracingConfig;

    /**
     * For Lambda@Edge functions, the ARN of the main function.
     *
     * @var string|null
     */
    private $masterArn;

    /**
     * The latest updated revision of the function or alias.
     *
     * @var string|null
     */
    private $revisionId;

    /**
     * The function's layers [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     *
     * @var Layer[]|null
     */
    private $layers;

    /**
     * The current state of the function. When the state is `Inactive`, you can reactivate the function by invoking it.
     *
     * @var State::*|null
     */
    private $state;

    /**
     * The reason for the function's current state.
     *
     * @var string|null
     */
    private $stateReason;

    /**
     * The reason code for the function's current state. When the code is `Creating`, you can't invoke or modify the
     * function.
     *
     * @var StateReasonCode::*|null
     */
    private $stateReasonCode;

    /**
     * The status of the last update that was performed on the function. This is first set to `Successful` after function
     * creation completes.
     *
     * @var LastUpdateStatus::*|null
     */
    private $lastUpdateStatus;

    /**
     * The reason for the last update that was performed on the function.
     *
     * @var string|null
     */
    private $lastUpdateStatusReason;

    /**
     * The reason code for the last update that was performed on the function.
     *
     * @var LastUpdateStatusReasonCode::*|null
     */
    private $lastUpdateStatusReasonCode;

    /**
     * Connection settings for an Amazon EFS file system [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-filesystem.html
     *
     * @var FileSystemConfig[]|null
     */
    private $fileSystemConfigs;

    /**
     * The type of deployment package. Set to `Image` for container image and set `Zip` for .zip file archive.
     *
     * @var PackageType::*|null
     */
    private $packageType;

    /**
     * The function's image configuration values.
     *
     * @var ImageConfigResponse|null
     */
    private $imageConfigResponse;

    /**
     * The ARN of the signing profile version.
     *
     * @var string|null
     */
    private $signingProfileVersionArn;

    /**
     * The ARN of the signing job.
     *
     * @var string|null
     */
    private $signingJobArn;

    /**
     * The instruction set architecture that the function supports. Architecture is a string array with one of the valid
     * values. The default architecture value is `x86_64`.
     *
     * @var list<Architecture::*>|null
     */
    private $architectures;

    /**
     * The size of the function’s `/tmp` directory in MB. The default value is 512, but it can be any whole number between
     * 512 and 10,240 MB.
     *
     * @var EphemeralStorage|null
     */
    private $ephemeralStorage;

    /**
     * Set `ApplyOn` to `PublishedVersions` to create a snapshot of the initialized execution environment when you publish a
     * function version. For more information, see Improving startup performance with Lambda SnapStart [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart.html
     *
     * @var SnapStartResponse|null
     */
    private $snapStart;

    /**
     * The ARN of the runtime and any errors that occured.
     *
     * @var RuntimeVersionConfig|null
     */
    private $runtimeVersionConfig;

    /**
     * The function's Amazon CloudWatch Logs configuration settings.
     *
     * @var LoggingConfig|null
     */
    private $loggingConfig;

    /**
     * @param array{
     *   FunctionName?: null|string,
     *   FunctionArn?: null|string,
     *   Runtime?: null|Runtime::*,
     *   Role?: null|string,
     *   Handler?: null|string,
     *   CodeSize?: null|int,
     *   Description?: null|string,
     *   Timeout?: null|int,
     *   MemorySize?: null|int,
     *   LastModified?: null|string,
     *   CodeSha256?: null|string,
     *   Version?: null|string,
     *   VpcConfig?: null|VpcConfigResponse|array,
     *   DeadLetterConfig?: null|DeadLetterConfig|array,
     *   Environment?: null|EnvironmentResponse|array,
     *   KMSKeyArn?: null|string,
     *   TracingConfig?: null|TracingConfigResponse|array,
     *   MasterArn?: null|string,
     *   RevisionId?: null|string,
     *   Layers?: null|array<Layer|array>,
     *   State?: null|State::*,
     *   StateReason?: null|string,
     *   StateReasonCode?: null|StateReasonCode::*,
     *   LastUpdateStatus?: null|LastUpdateStatus::*,
     *   LastUpdateStatusReason?: null|string,
     *   LastUpdateStatusReasonCode?: null|LastUpdateStatusReasonCode::*,
     *   FileSystemConfigs?: null|array<FileSystemConfig|array>,
     *   PackageType?: null|PackageType::*,
     *   ImageConfigResponse?: null|ImageConfigResponse|array,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
     *   Architectures?: null|array<Architecture::*>,
     *   EphemeralStorage?: null|EphemeralStorage|array,
     *   SnapStart?: null|SnapStartResponse|array,
     *   RuntimeVersionConfig?: null|RuntimeVersionConfig|array,
     *   LoggingConfig?: null|LoggingConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->functionName = $input['FunctionName'] ?? null;
        $this->functionArn = $input['FunctionArn'] ?? null;
        $this->runtime = $input['Runtime'] ?? null;
        $this->role = $input['Role'] ?? null;
        $this->handler = $input['Handler'] ?? null;
        $this->codeSize = $input['CodeSize'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->timeout = $input['Timeout'] ?? null;
        $this->memorySize = $input['MemorySize'] ?? null;
        $this->lastModified = $input['LastModified'] ?? null;
        $this->codeSha256 = $input['CodeSha256'] ?? null;
        $this->version = $input['Version'] ?? null;
        $this->vpcConfig = isset($input['VpcConfig']) ? VpcConfigResponse::create($input['VpcConfig']) : null;
        $this->deadLetterConfig = isset($input['DeadLetterConfig']) ? DeadLetterConfig::create($input['DeadLetterConfig']) : null;
        $this->environment = isset($input['Environment']) ? EnvironmentResponse::create($input['Environment']) : null;
        $this->kmsKeyArn = $input['KMSKeyArn'] ?? null;
        $this->tracingConfig = isset($input['TracingConfig']) ? TracingConfigResponse::create($input['TracingConfig']) : null;
        $this->masterArn = $input['MasterArn'] ?? null;
        $this->revisionId = $input['RevisionId'] ?? null;
        $this->layers = isset($input['Layers']) ? array_map([Layer::class, 'create'], $input['Layers']) : null;
        $this->state = $input['State'] ?? null;
        $this->stateReason = $input['StateReason'] ?? null;
        $this->stateReasonCode = $input['StateReasonCode'] ?? null;
        $this->lastUpdateStatus = $input['LastUpdateStatus'] ?? null;
        $this->lastUpdateStatusReason = $input['LastUpdateStatusReason'] ?? null;
        $this->lastUpdateStatusReasonCode = $input['LastUpdateStatusReasonCode'] ?? null;
        $this->fileSystemConfigs = isset($input['FileSystemConfigs']) ? array_map([FileSystemConfig::class, 'create'], $input['FileSystemConfigs']) : null;
        $this->packageType = $input['PackageType'] ?? null;
        $this->imageConfigResponse = isset($input['ImageConfigResponse']) ? ImageConfigResponse::create($input['ImageConfigResponse']) : null;
        $this->signingProfileVersionArn = $input['SigningProfileVersionArn'] ?? null;
        $this->signingJobArn = $input['SigningJobArn'] ?? null;
        $this->architectures = $input['Architectures'] ?? null;
        $this->ephemeralStorage = isset($input['EphemeralStorage']) ? EphemeralStorage::create($input['EphemeralStorage']) : null;
        $this->snapStart = isset($input['SnapStart']) ? SnapStartResponse::create($input['SnapStart']) : null;
        $this->runtimeVersionConfig = isset($input['RuntimeVersionConfig']) ? RuntimeVersionConfig::create($input['RuntimeVersionConfig']) : null;
        $this->loggingConfig = isset($input['LoggingConfig']) ? LoggingConfig::create($input['LoggingConfig']) : null;
    }

    /**
     * @param array{
     *   FunctionName?: null|string,
     *   FunctionArn?: null|string,
     *   Runtime?: null|Runtime::*,
     *   Role?: null|string,
     *   Handler?: null|string,
     *   CodeSize?: null|int,
     *   Description?: null|string,
     *   Timeout?: null|int,
     *   MemorySize?: null|int,
     *   LastModified?: null|string,
     *   CodeSha256?: null|string,
     *   Version?: null|string,
     *   VpcConfig?: null|VpcConfigResponse|array,
     *   DeadLetterConfig?: null|DeadLetterConfig|array,
     *   Environment?: null|EnvironmentResponse|array,
     *   KMSKeyArn?: null|string,
     *   TracingConfig?: null|TracingConfigResponse|array,
     *   MasterArn?: null|string,
     *   RevisionId?: null|string,
     *   Layers?: null|array<Layer|array>,
     *   State?: null|State::*,
     *   StateReason?: null|string,
     *   StateReasonCode?: null|StateReasonCode::*,
     *   LastUpdateStatus?: null|LastUpdateStatus::*,
     *   LastUpdateStatusReason?: null|string,
     *   LastUpdateStatusReasonCode?: null|LastUpdateStatusReasonCode::*,
     *   FileSystemConfigs?: null|array<FileSystemConfig|array>,
     *   PackageType?: null|PackageType::*,
     *   ImageConfigResponse?: null|ImageConfigResponse|array,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
     *   Architectures?: null|array<Architecture::*>,
     *   EphemeralStorage?: null|EphemeralStorage|array,
     *   SnapStart?: null|SnapStartResponse|array,
     *   RuntimeVersionConfig?: null|RuntimeVersionConfig|array,
     *   LoggingConfig?: null|LoggingConfig|array,
     * }|FunctionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Architecture::*>
     */
    public function getArchitectures(): array
    {
        return $this->architectures ?? [];
    }

    public function getCodeSha256(): ?string
    {
        return $this->codeSha256;
    }

    public function getCodeSize(): ?int
    {
        return $this->codeSize;
    }

    public function getDeadLetterConfig(): ?DeadLetterConfig
    {
        return $this->deadLetterConfig;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEnvironment(): ?EnvironmentResponse
    {
        return $this->environment;
    }

    public function getEphemeralStorage(): ?EphemeralStorage
    {
        return $this->ephemeralStorage;
    }

    /**
     * @return FileSystemConfig[]
     */
    public function getFileSystemConfigs(): array
    {
        return $this->fileSystemConfigs ?? [];
    }

    public function getFunctionArn(): ?string
    {
        return $this->functionArn;
    }

    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

    public function getHandler(): ?string
    {
        return $this->handler;
    }

    public function getImageConfigResponse(): ?ImageConfigResponse
    {
        return $this->imageConfigResponse;
    }

    public function getKmsKeyArn(): ?string
    {
        return $this->kmsKeyArn;
    }

    public function getLastModified(): ?string
    {
        return $this->lastModified;
    }

    /**
     * @return LastUpdateStatus::*|null
     */
    public function getLastUpdateStatus(): ?string
    {
        return $this->lastUpdateStatus;
    }

    public function getLastUpdateStatusReason(): ?string
    {
        return $this->lastUpdateStatusReason;
    }

    /**
     * @return LastUpdateStatusReasonCode::*|null
     */
    public function getLastUpdateStatusReasonCode(): ?string
    {
        return $this->lastUpdateStatusReasonCode;
    }

    /**
     * @return Layer[]
     */
    public function getLayers(): array
    {
        return $this->layers ?? [];
    }

    public function getLoggingConfig(): ?LoggingConfig
    {
        return $this->loggingConfig;
    }

    public function getMasterArn(): ?string
    {
        return $this->masterArn;
    }

    public function getMemorySize(): ?int
    {
        return $this->memorySize;
    }

    /**
     * @return PackageType::*|null
     */
    public function getPackageType(): ?string
    {
        return $this->packageType;
    }

    public function getRevisionId(): ?string
    {
        return $this->revisionId;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @return Runtime::*|null
     */
    public function getRuntime(): ?string
    {
        return $this->runtime;
    }

    public function getRuntimeVersionConfig(): ?RuntimeVersionConfig
    {
        return $this->runtimeVersionConfig;
    }

    public function getSigningJobArn(): ?string
    {
        return $this->signingJobArn;
    }

    public function getSigningProfileVersionArn(): ?string
    {
        return $this->signingProfileVersionArn;
    }

    public function getSnapStart(): ?SnapStartResponse
    {
        return $this->snapStart;
    }

    /**
     * @return State::*|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStateReason(): ?string
    {
        return $this->stateReason;
    }

    /**
     * @return StateReasonCode::*|null
     */
    public function getStateReasonCode(): ?string
    {
        return $this->stateReasonCode;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function getTracingConfig(): ?TracingConfigResponse
    {
        return $this->tracingConfig;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getVpcConfig(): ?VpcConfigResponse
    {
        return $this->vpcConfig;
    }
}
