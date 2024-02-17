<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\LastUpdateStatus;
use AsyncAws\Lambda\Enum\LastUpdateStatusReasonCode;
use AsyncAws\Lambda\Enum\PackageType;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Enum\State;
use AsyncAws\Lambda\Enum\StateReasonCode;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\EnvironmentError;
use AsyncAws\Lambda\ValueObject\EnvironmentResponse;
use AsyncAws\Lambda\ValueObject\EphemeralStorage;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\ImageConfigError;
use AsyncAws\Lambda\ValueObject\ImageConfigResponse;
use AsyncAws\Lambda\ValueObject\Layer;
use AsyncAws\Lambda\ValueObject\LoggingConfig;
use AsyncAws\Lambda\ValueObject\RuntimeVersionConfig;
use AsyncAws\Lambda\ValueObject\RuntimeVersionError;
use AsyncAws\Lambda\ValueObject\SnapStartResponse;
use AsyncAws\Lambda\ValueObject\TracingConfigResponse;
use AsyncAws\Lambda\ValueObject\VpcConfigResponse;

/**
 * Details about a function's configuration.
 */
class FunctionConfiguration extends Result
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
     * @var Layer[]
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
     * @var FileSystemConfig[]
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
     * @var list<Architecture::*>
     */
    private $architectures;

    /**
     * The size of the function's `/tmp` directory in MB. The default value is 512, but can be any whole number between 512
     * and 10,240 MB. For more information, see Configuring ephemeral storage (console) [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-function-common.html#configuration-ephemeral-storage
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
     * @return list<Architecture::*>
     */
    public function getArchitectures(): array
    {
        $this->initialize();

        return $this->architectures;
    }

    public function getCodeSha256(): ?string
    {
        $this->initialize();

        return $this->codeSha256;
    }

    public function getCodeSize(): ?int
    {
        $this->initialize();

        return $this->codeSize;
    }

    public function getDeadLetterConfig(): ?DeadLetterConfig
    {
        $this->initialize();

        return $this->deadLetterConfig;
    }

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->description;
    }

    public function getEnvironment(): ?EnvironmentResponse
    {
        $this->initialize();

        return $this->environment;
    }

    public function getEphemeralStorage(): ?EphemeralStorage
    {
        $this->initialize();

        return $this->ephemeralStorage;
    }

    /**
     * @return FileSystemConfig[]
     */
    public function getFileSystemConfigs(): array
    {
        $this->initialize();

        return $this->fileSystemConfigs;
    }

    public function getFunctionArn(): ?string
    {
        $this->initialize();

        return $this->functionArn;
    }

    public function getFunctionName(): ?string
    {
        $this->initialize();

        return $this->functionName;
    }

    public function getHandler(): ?string
    {
        $this->initialize();

        return $this->handler;
    }

    public function getImageConfigResponse(): ?ImageConfigResponse
    {
        $this->initialize();

        return $this->imageConfigResponse;
    }

    public function getKmsKeyArn(): ?string
    {
        $this->initialize();

        return $this->kmsKeyArn;
    }

    public function getLastModified(): ?string
    {
        $this->initialize();

        return $this->lastModified;
    }

    /**
     * @return LastUpdateStatus::*|null
     */
    public function getLastUpdateStatus(): ?string
    {
        $this->initialize();

        return $this->lastUpdateStatus;
    }

    public function getLastUpdateStatusReason(): ?string
    {
        $this->initialize();

        return $this->lastUpdateStatusReason;
    }

    /**
     * @return LastUpdateStatusReasonCode::*|null
     */
    public function getLastUpdateStatusReasonCode(): ?string
    {
        $this->initialize();

        return $this->lastUpdateStatusReasonCode;
    }

    /**
     * @return Layer[]
     */
    public function getLayers(): array
    {
        $this->initialize();

        return $this->layers;
    }

    public function getLoggingConfig(): ?LoggingConfig
    {
        $this->initialize();

        return $this->loggingConfig;
    }

    public function getMasterArn(): ?string
    {
        $this->initialize();

        return $this->masterArn;
    }

    public function getMemorySize(): ?int
    {
        $this->initialize();

        return $this->memorySize;
    }

    /**
     * @return PackageType::*|null
     */
    public function getPackageType(): ?string
    {
        $this->initialize();

        return $this->packageType;
    }

    public function getRevisionId(): ?string
    {
        $this->initialize();

        return $this->revisionId;
    }

    public function getRole(): ?string
    {
        $this->initialize();

        return $this->role;
    }

    /**
     * @return Runtime::*|null
     */
    public function getRuntime(): ?string
    {
        $this->initialize();

        return $this->runtime;
    }

    public function getRuntimeVersionConfig(): ?RuntimeVersionConfig
    {
        $this->initialize();

        return $this->runtimeVersionConfig;
    }

    public function getSigningJobArn(): ?string
    {
        $this->initialize();

        return $this->signingJobArn;
    }

    public function getSigningProfileVersionArn(): ?string
    {
        $this->initialize();

        return $this->signingProfileVersionArn;
    }

    public function getSnapStart(): ?SnapStartResponse
    {
        $this->initialize();

        return $this->snapStart;
    }

    /**
     * @return State::*|null
     */
    public function getState(): ?string
    {
        $this->initialize();

        return $this->state;
    }

    public function getStateReason(): ?string
    {
        $this->initialize();

        return $this->stateReason;
    }

    /**
     * @return StateReasonCode::*|null
     */
    public function getStateReasonCode(): ?string
    {
        $this->initialize();

        return $this->stateReasonCode;
    }

    public function getTimeout(): ?int
    {
        $this->initialize();

        return $this->timeout;
    }

    public function getTracingConfig(): ?TracingConfigResponse
    {
        $this->initialize();

        return $this->tracingConfig;
    }

    public function getVersion(): ?string
    {
        $this->initialize();

        return $this->version;
    }

    public function getVpcConfig(): ?VpcConfigResponse
    {
        $this->initialize();

        return $this->vpcConfig;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->functionName = isset($data['FunctionName']) ? (string) $data['FunctionName'] : null;
        $this->functionArn = isset($data['FunctionArn']) ? (string) $data['FunctionArn'] : null;
        $this->runtime = isset($data['Runtime']) ? (string) $data['Runtime'] : null;
        $this->role = isset($data['Role']) ? (string) $data['Role'] : null;
        $this->handler = isset($data['Handler']) ? (string) $data['Handler'] : null;
        $this->codeSize = isset($data['CodeSize']) ? (int) $data['CodeSize'] : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->timeout = isset($data['Timeout']) ? (int) $data['Timeout'] : null;
        $this->memorySize = isset($data['MemorySize']) ? (int) $data['MemorySize'] : null;
        $this->lastModified = isset($data['LastModified']) ? (string) $data['LastModified'] : null;
        $this->codeSha256 = isset($data['CodeSha256']) ? (string) $data['CodeSha256'] : null;
        $this->version = isset($data['Version']) ? (string) $data['Version'] : null;
        $this->vpcConfig = empty($data['VpcConfig']) ? null : $this->populateResultVpcConfigResponse($data['VpcConfig']);
        $this->deadLetterConfig = empty($data['DeadLetterConfig']) ? null : $this->populateResultDeadLetterConfig($data['DeadLetterConfig']);
        $this->environment = empty($data['Environment']) ? null : $this->populateResultEnvironmentResponse($data['Environment']);
        $this->kmsKeyArn = isset($data['KMSKeyArn']) ? (string) $data['KMSKeyArn'] : null;
        $this->tracingConfig = empty($data['TracingConfig']) ? null : $this->populateResultTracingConfigResponse($data['TracingConfig']);
        $this->masterArn = isset($data['MasterArn']) ? (string) $data['MasterArn'] : null;
        $this->revisionId = isset($data['RevisionId']) ? (string) $data['RevisionId'] : null;
        $this->layers = empty($data['Layers']) ? [] : $this->populateResultLayersReferenceList($data['Layers']);
        $this->state = isset($data['State']) ? (string) $data['State'] : null;
        $this->stateReason = isset($data['StateReason']) ? (string) $data['StateReason'] : null;
        $this->stateReasonCode = isset($data['StateReasonCode']) ? (string) $data['StateReasonCode'] : null;
        $this->lastUpdateStatus = isset($data['LastUpdateStatus']) ? (string) $data['LastUpdateStatus'] : null;
        $this->lastUpdateStatusReason = isset($data['LastUpdateStatusReason']) ? (string) $data['LastUpdateStatusReason'] : null;
        $this->lastUpdateStatusReasonCode = isset($data['LastUpdateStatusReasonCode']) ? (string) $data['LastUpdateStatusReasonCode'] : null;
        $this->fileSystemConfigs = empty($data['FileSystemConfigs']) ? [] : $this->populateResultFileSystemConfigList($data['FileSystemConfigs']);
        $this->packageType = isset($data['PackageType']) ? (string) $data['PackageType'] : null;
        $this->imageConfigResponse = empty($data['ImageConfigResponse']) ? null : $this->populateResultImageConfigResponse($data['ImageConfigResponse']);
        $this->signingProfileVersionArn = isset($data['SigningProfileVersionArn']) ? (string) $data['SigningProfileVersionArn'] : null;
        $this->signingJobArn = isset($data['SigningJobArn']) ? (string) $data['SigningJobArn'] : null;
        $this->architectures = empty($data['Architectures']) ? [] : $this->populateResultArchitecturesList($data['Architectures']);
        $this->ephemeralStorage = empty($data['EphemeralStorage']) ? null : $this->populateResultEphemeralStorage($data['EphemeralStorage']);
        $this->snapStart = empty($data['SnapStart']) ? null : $this->populateResultSnapStartResponse($data['SnapStart']);
        $this->runtimeVersionConfig = empty($data['RuntimeVersionConfig']) ? null : $this->populateResultRuntimeVersionConfig($data['RuntimeVersionConfig']);
        $this->loggingConfig = empty($data['LoggingConfig']) ? null : $this->populateResultLoggingConfig($data['LoggingConfig']);
    }

    /**
     * @return list<Architecture::*>
     */
    private function populateResultArchitecturesList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultDeadLetterConfig(array $json): DeadLetterConfig
    {
        return new DeadLetterConfig([
            'TargetArn' => isset($json['TargetArn']) ? (string) $json['TargetArn'] : null,
        ]);
    }

    private function populateResultEnvironmentError(array $json): EnvironmentError
    {
        return new EnvironmentError([
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    private function populateResultEnvironmentResponse(array $json): EnvironmentResponse
    {
        return new EnvironmentResponse([
            'Variables' => !isset($json['Variables']) ? null : $this->populateResultEnvironmentVariables($json['Variables']),
            'Error' => empty($json['Error']) ? null : $this->populateResultEnvironmentError($json['Error']),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultEnvironmentVariables(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultEphemeralStorage(array $json): EphemeralStorage
    {
        return new EphemeralStorage([
            'Size' => (int) $json['Size'],
        ]);
    }

    private function populateResultFileSystemConfig(array $json): FileSystemConfig
    {
        return new FileSystemConfig([
            'Arn' => (string) $json['Arn'],
            'LocalMountPath' => (string) $json['LocalMountPath'],
        ]);
    }

    /**
     * @return FileSystemConfig[]
     */
    private function populateResultFileSystemConfigList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFileSystemConfig($item);
        }

        return $items;
    }

    private function populateResultImageConfig(array $json): ImageConfig
    {
        return new ImageConfig([
            'EntryPoint' => !isset($json['EntryPoint']) ? null : $this->populateResultStringList($json['EntryPoint']),
            'Command' => !isset($json['Command']) ? null : $this->populateResultStringList($json['Command']),
            'WorkingDirectory' => isset($json['WorkingDirectory']) ? (string) $json['WorkingDirectory'] : null,
        ]);
    }

    private function populateResultImageConfigError(array $json): ImageConfigError
    {
        return new ImageConfigError([
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    private function populateResultImageConfigResponse(array $json): ImageConfigResponse
    {
        return new ImageConfigResponse([
            'ImageConfig' => empty($json['ImageConfig']) ? null : $this->populateResultImageConfig($json['ImageConfig']),
            'Error' => empty($json['Error']) ? null : $this->populateResultImageConfigError($json['Error']),
        ]);
    }

    private function populateResultLayer(array $json): Layer
    {
        return new Layer([
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
            'CodeSize' => isset($json['CodeSize']) ? (int) $json['CodeSize'] : null,
            'SigningProfileVersionArn' => isset($json['SigningProfileVersionArn']) ? (string) $json['SigningProfileVersionArn'] : null,
            'SigningJobArn' => isset($json['SigningJobArn']) ? (string) $json['SigningJobArn'] : null,
        ]);
    }

    /**
     * @return Layer[]
     */
    private function populateResultLayersReferenceList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultLayer($item);
        }

        return $items;
    }

    private function populateResultLoggingConfig(array $json): LoggingConfig
    {
        return new LoggingConfig([
            'LogFormat' => isset($json['LogFormat']) ? (string) $json['LogFormat'] : null,
            'ApplicationLogLevel' => isset($json['ApplicationLogLevel']) ? (string) $json['ApplicationLogLevel'] : null,
            'SystemLogLevel' => isset($json['SystemLogLevel']) ? (string) $json['SystemLogLevel'] : null,
            'LogGroup' => isset($json['LogGroup']) ? (string) $json['LogGroup'] : null,
        ]);
    }

    private function populateResultRuntimeVersionConfig(array $json): RuntimeVersionConfig
    {
        return new RuntimeVersionConfig([
            'RuntimeVersionArn' => isset($json['RuntimeVersionArn']) ? (string) $json['RuntimeVersionArn'] : null,
            'Error' => empty($json['Error']) ? null : $this->populateResultRuntimeVersionError($json['Error']),
        ]);
    }

    private function populateResultRuntimeVersionError(array $json): RuntimeVersionError
    {
        return new RuntimeVersionError([
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultSecurityGroupIds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultSnapStartResponse(array $json): SnapStartResponse
    {
        return new SnapStartResponse([
            'ApplyOn' => isset($json['ApplyOn']) ? (string) $json['ApplyOn'] : null,
            'OptimizationStatus' => isset($json['OptimizationStatus']) ? (string) $json['OptimizationStatus'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultStringList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultSubnetIds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultTracingConfigResponse(array $json): TracingConfigResponse
    {
        return new TracingConfigResponse([
            'Mode' => isset($json['Mode']) ? (string) $json['Mode'] : null,
        ]);
    }

    private function populateResultVpcConfigResponse(array $json): VpcConfigResponse
    {
        return new VpcConfigResponse([
            'SubnetIds' => !isset($json['SubnetIds']) ? null : $this->populateResultSubnetIds($json['SubnetIds']),
            'SecurityGroupIds' => !isset($json['SecurityGroupIds']) ? null : $this->populateResultSecurityGroupIds($json['SecurityGroupIds']),
            'VpcId' => isset($json['VpcId']) ? (string) $json['VpcId'] : null,
            'Ipv6AllowedForDualStack' => isset($json['Ipv6AllowedForDualStack']) ? filter_var($json['Ipv6AllowedForDualStack'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }
}
