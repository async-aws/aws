<?php

namespace AsyncAws\Lambda\ValueObject;

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
     */
    private $functionName;

    /**
     * The function's Amazon Resource Name (ARN).
     */
    private $functionArn;

    /**
     * The runtime environment for the Lambda function.
     */
    private $runtime;

    /**
     * The function's execution role.
     */
    private $role;

    /**
     * The function that Lambda calls to begin executing your function.
     */
    private $handler;

    /**
     * The size of the function's deployment package, in bytes.
     */
    private $codeSize;

    /**
     * The function's description.
     */
    private $description;

    /**
     * The amount of time in seconds that Lambda allows a function to run before stopping it.
     */
    private $timeout;

    /**
     * The amount of memory available to the function at runtime.
     */
    private $memorySize;

    /**
     * The date and time that the function was last updated, in ISO-8601 format (YYYY-MM-DDThh:mm:ss.sTZD).
     *
     * @see https://www.w3.org/TR/NOTE-datetime
     */
    private $lastModified;

    /**
     * The SHA256 hash of the function's deployment package.
     */
    private $codeSha256;

    /**
     * The version of the Lambda function.
     */
    private $version;

    /**
     * The function's networking configuration.
     */
    private $vpcConfig;

    /**
     * The function's dead letter queue.
     */
    private $deadLetterConfig;

    /**
     * The function's environment variables.
     */
    private $environment;

    /**
     * The KMS key that's used to encrypt the function's environment variables. This key is only returned if you've
     * configured a customer managed CMK.
     */
    private $kmsKeyArn;

    /**
     * The function's AWS X-Ray tracing configuration.
     */
    private $tracingConfig;

    /**
     * For Lambda@Edge functions, the ARN of the master function.
     */
    private $masterArn;

    /**
     * The latest updated revision of the function or alias.
     */
    private $revisionId;

    /**
     * The function's  layers.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
     */
    private $layers;

    /**
     * The current state of the function. When the state is `Inactive`, you can reactivate the function by invoking it.
     */
    private $state;

    /**
     * The reason for the function's current state.
     */
    private $stateReason;

    /**
     * The reason code for the function's current state. When the code is `Creating`, you can't invoke or modify the
     * function.
     */
    private $stateReasonCode;

    /**
     * The status of the last update that was performed on the function. This is first set to `Successful` after function
     * creation completes.
     */
    private $lastUpdateStatus;

    /**
     * The reason for the last update that was performed on the function.
     */
    private $lastUpdateStatusReason;

    /**
     * The reason code for the last update that was performed on the function.
     */
    private $lastUpdateStatusReasonCode;

    /**
     * Connection settings for an Amazon EFS file system.
     */
    private $fileSystemConfigs;

    /**
     * The type of deployment package. Set to `Image` for container image and set `Zip` for .zip file archive.
     */
    private $packageType;

    /**
     * The function's image configuration values.
     */
    private $imageConfigResponse;

    /**
     * The ARN of the signing profile version.
     */
    private $signingProfileVersionArn;

    /**
     * The ARN of the signing job.
     */
    private $signingJobArn;

    /**
     * @param array{
     *   FunctionName?: null|string,
     *   FunctionArn?: null|string,
     *   Runtime?: null|Runtime::*,
     *   Role?: null|string,
     *   Handler?: null|string,
     *   CodeSize?: null|string,
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
     *   Layers?: null|Layer[],
     *   State?: null|State::*,
     *   StateReason?: null|string,
     *   StateReasonCode?: null|StateReasonCode::*,
     *   LastUpdateStatus?: null|LastUpdateStatus::*,
     *   LastUpdateStatusReason?: null|string,
     *   LastUpdateStatusReasonCode?: null|LastUpdateStatusReasonCode::*,
     *   FileSystemConfigs?: null|FileSystemConfig[],
     *   PackageType?: null|PackageType::*,
     *   ImageConfigResponse?: null|ImageConfigResponse|array,
     *   SigningProfileVersionArn?: null|string,
     *   SigningJobArn?: null|string,
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
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCodeSha256(): ?string
    {
        return $this->codeSha256;
    }

    public function getCodeSize(): ?string
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

    public function getSigningJobArn(): ?string
    {
        return $this->signingJobArn;
    }

    public function getSigningProfileVersionArn(): ?string
    {
        return $this->signingProfileVersionArn;
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
