<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\ApplicationLogLevel;
use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\LastUpdateStatus;
use AsyncAws\Lambda\Enum\LastUpdateStatusReasonCode;
use AsyncAws\Lambda\Enum\LogFormat;
use AsyncAws\Lambda\Enum\PackageType;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Enum\SnapStartApplyOn;
use AsyncAws\Lambda\Enum\SnapStartOptimizationStatus;
use AsyncAws\Lambda\Enum\State;
use AsyncAws\Lambda\Enum\StateReasonCode;
use AsyncAws\Lambda\Enum\SystemLogLevel;
use AsyncAws\Lambda\Enum\TenantIsolationMode;
use AsyncAws\Lambda\Enum\TracingMode;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\ValueObject\CapacityProviderConfig;
use AsyncAws\Lambda\ValueObject\DeadLetterConfig;
use AsyncAws\Lambda\ValueObject\DurableConfig;
use AsyncAws\Lambda\ValueObject\EnvironmentError;
use AsyncAws\Lambda\ValueObject\EnvironmentResponse;
use AsyncAws\Lambda\ValueObject\EphemeralStorage;
use AsyncAws\Lambda\ValueObject\FileSystemConfig;
use AsyncAws\Lambda\ValueObject\FunctionConfiguration;
use AsyncAws\Lambda\ValueObject\ImageConfig;
use AsyncAws\Lambda\ValueObject\ImageConfigError;
use AsyncAws\Lambda\ValueObject\ImageConfigResponse;
use AsyncAws\Lambda\ValueObject\LambdaManagedInstancesCapacityProviderConfig;
use AsyncAws\Lambda\ValueObject\Layer;
use AsyncAws\Lambda\ValueObject\LoggingConfig;
use AsyncAws\Lambda\ValueObject\RuntimeVersionConfig;
use AsyncAws\Lambda\ValueObject\RuntimeVersionError;
use AsyncAws\Lambda\ValueObject\SnapStartResponse;
use AsyncAws\Lambda\ValueObject\TenancyConfig;
use AsyncAws\Lambda\ValueObject\TracingConfigResponse;
use AsyncAws\Lambda\ValueObject\VpcConfigResponse;

/**
 * @implements \IteratorAggregate<FunctionConfiguration>
 */
class ListVersionsByFunctionResponse extends Result implements \IteratorAggregate
{
    /**
     * The pagination token that's included if more results are available.
     *
     * @var string|null
     */
    private $nextMarker;

    /**
     * A list of Lambda function versions.
     *
     * @var FunctionConfiguration[]
     */
    private $versions;

    /**
     * Iterates over Versions.
     *
     * @return \Traversable<FunctionConfiguration>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getVersions();
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->nextMarker;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<FunctionConfiguration>
     */
    public function getVersions(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->versions;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListVersionsByFunctionRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextMarker) {
                $input->setMarker($page->nextMarker);

                $this->registerPrefetch($nextPage = $client->listVersionsByFunction($input));
            } else {
                $nextPage = null;
            }

            yield from $page->versions;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->versions = empty($data['Versions']) ? [] : $this->populateResultFunctionList($data['Versions']);
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
                if (!Architecture::exists($a)) {
                    $a = Architecture::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultCapacityProviderConfig(array $json): CapacityProviderConfig
    {
        return new CapacityProviderConfig([
            'LambdaManagedInstancesCapacityProviderConfig' => $this->populateResultLambdaManagedInstancesCapacityProviderConfig($json['LambdaManagedInstancesCapacityProviderConfig']),
        ]);
    }

    private function populateResultDeadLetterConfig(array $json): DeadLetterConfig
    {
        return new DeadLetterConfig([
            'TargetArn' => isset($json['TargetArn']) ? (string) $json['TargetArn'] : null,
        ]);
    }

    private function populateResultDurableConfig(array $json): DurableConfig
    {
        return new DurableConfig([
            'RetentionPeriodInDays' => isset($json['RetentionPeriodInDays']) ? (int) $json['RetentionPeriodInDays'] : null,
            'ExecutionTimeout' => isset($json['ExecutionTimeout']) ? (int) $json['ExecutionTimeout'] : null,
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

    private function populateResultFunctionConfiguration(array $json): FunctionConfiguration
    {
        return new FunctionConfiguration([
            'FunctionName' => isset($json['FunctionName']) ? (string) $json['FunctionName'] : null,
            'FunctionArn' => isset($json['FunctionArn']) ? (string) $json['FunctionArn'] : null,
            'Runtime' => isset($json['Runtime']) ? (!Runtime::exists((string) $json['Runtime']) ? Runtime::UNKNOWN_TO_SDK : (string) $json['Runtime']) : null,
            'Role' => isset($json['Role']) ? (string) $json['Role'] : null,
            'Handler' => isset($json['Handler']) ? (string) $json['Handler'] : null,
            'CodeSize' => isset($json['CodeSize']) ? (int) $json['CodeSize'] : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'Timeout' => isset($json['Timeout']) ? (int) $json['Timeout'] : null,
            'MemorySize' => isset($json['MemorySize']) ? (int) $json['MemorySize'] : null,
            'LastModified' => isset($json['LastModified']) ? (string) $json['LastModified'] : null,
            'CodeSha256' => isset($json['CodeSha256']) ? (string) $json['CodeSha256'] : null,
            'Version' => isset($json['Version']) ? (string) $json['Version'] : null,
            'VpcConfig' => empty($json['VpcConfig']) ? null : $this->populateResultVpcConfigResponse($json['VpcConfig']),
            'DeadLetterConfig' => empty($json['DeadLetterConfig']) ? null : $this->populateResultDeadLetterConfig($json['DeadLetterConfig']),
            'Environment' => empty($json['Environment']) ? null : $this->populateResultEnvironmentResponse($json['Environment']),
            'KMSKeyArn' => isset($json['KMSKeyArn']) ? (string) $json['KMSKeyArn'] : null,
            'TracingConfig' => empty($json['TracingConfig']) ? null : $this->populateResultTracingConfigResponse($json['TracingConfig']),
            'MasterArn' => isset($json['MasterArn']) ? (string) $json['MasterArn'] : null,
            'RevisionId' => isset($json['RevisionId']) ? (string) $json['RevisionId'] : null,
            'Layers' => !isset($json['Layers']) ? null : $this->populateResultLayersReferenceList($json['Layers']),
            'State' => isset($json['State']) ? (!State::exists((string) $json['State']) ? State::UNKNOWN_TO_SDK : (string) $json['State']) : null,
            'StateReason' => isset($json['StateReason']) ? (string) $json['StateReason'] : null,
            'StateReasonCode' => isset($json['StateReasonCode']) ? (!StateReasonCode::exists((string) $json['StateReasonCode']) ? StateReasonCode::UNKNOWN_TO_SDK : (string) $json['StateReasonCode']) : null,
            'LastUpdateStatus' => isset($json['LastUpdateStatus']) ? (!LastUpdateStatus::exists((string) $json['LastUpdateStatus']) ? LastUpdateStatus::UNKNOWN_TO_SDK : (string) $json['LastUpdateStatus']) : null,
            'LastUpdateStatusReason' => isset($json['LastUpdateStatusReason']) ? (string) $json['LastUpdateStatusReason'] : null,
            'LastUpdateStatusReasonCode' => isset($json['LastUpdateStatusReasonCode']) ? (!LastUpdateStatusReasonCode::exists((string) $json['LastUpdateStatusReasonCode']) ? LastUpdateStatusReasonCode::UNKNOWN_TO_SDK : (string) $json['LastUpdateStatusReasonCode']) : null,
            'FileSystemConfigs' => !isset($json['FileSystemConfigs']) ? null : $this->populateResultFileSystemConfigList($json['FileSystemConfigs']),
            'PackageType' => isset($json['PackageType']) ? (!PackageType::exists((string) $json['PackageType']) ? PackageType::UNKNOWN_TO_SDK : (string) $json['PackageType']) : null,
            'ImageConfigResponse' => empty($json['ImageConfigResponse']) ? null : $this->populateResultImageConfigResponse($json['ImageConfigResponse']),
            'SigningProfileVersionArn' => isset($json['SigningProfileVersionArn']) ? (string) $json['SigningProfileVersionArn'] : null,
            'SigningJobArn' => isset($json['SigningJobArn']) ? (string) $json['SigningJobArn'] : null,
            'Architectures' => !isset($json['Architectures']) ? null : $this->populateResultArchitecturesList($json['Architectures']),
            'EphemeralStorage' => empty($json['EphemeralStorage']) ? null : $this->populateResultEphemeralStorage($json['EphemeralStorage']),
            'SnapStart' => empty($json['SnapStart']) ? null : $this->populateResultSnapStartResponse($json['SnapStart']),
            'RuntimeVersionConfig' => empty($json['RuntimeVersionConfig']) ? null : $this->populateResultRuntimeVersionConfig($json['RuntimeVersionConfig']),
            'LoggingConfig' => empty($json['LoggingConfig']) ? null : $this->populateResultLoggingConfig($json['LoggingConfig']),
            'CapacityProviderConfig' => empty($json['CapacityProviderConfig']) ? null : $this->populateResultCapacityProviderConfig($json['CapacityProviderConfig']),
            'ConfigSha256' => isset($json['ConfigSha256']) ? (string) $json['ConfigSha256'] : null,
            'DurableConfig' => empty($json['DurableConfig']) ? null : $this->populateResultDurableConfig($json['DurableConfig']),
            'TenancyConfig' => empty($json['TenancyConfig']) ? null : $this->populateResultTenancyConfig($json['TenancyConfig']),
        ]);
    }

    /**
     * @return FunctionConfiguration[]
     */
    private function populateResultFunctionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFunctionConfiguration($item);
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

    private function populateResultLambdaManagedInstancesCapacityProviderConfig(array $json): LambdaManagedInstancesCapacityProviderConfig
    {
        return new LambdaManagedInstancesCapacityProviderConfig([
            'CapacityProviderArn' => (string) $json['CapacityProviderArn'],
            'PerExecutionEnvironmentMaxConcurrency' => isset($json['PerExecutionEnvironmentMaxConcurrency']) ? (int) $json['PerExecutionEnvironmentMaxConcurrency'] : null,
            'ExecutionEnvironmentMemoryGiBPerVCpu' => isset($json['ExecutionEnvironmentMemoryGiBPerVCpu']) ? (float) $json['ExecutionEnvironmentMemoryGiBPerVCpu'] : null,
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
            'LogFormat' => isset($json['LogFormat']) ? (!LogFormat::exists((string) $json['LogFormat']) ? LogFormat::UNKNOWN_TO_SDK : (string) $json['LogFormat']) : null,
            'ApplicationLogLevel' => isset($json['ApplicationLogLevel']) ? (!ApplicationLogLevel::exists((string) $json['ApplicationLogLevel']) ? ApplicationLogLevel::UNKNOWN_TO_SDK : (string) $json['ApplicationLogLevel']) : null,
            'SystemLogLevel' => isset($json['SystemLogLevel']) ? (!SystemLogLevel::exists((string) $json['SystemLogLevel']) ? SystemLogLevel::UNKNOWN_TO_SDK : (string) $json['SystemLogLevel']) : null,
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
            'ApplyOn' => isset($json['ApplyOn']) ? (!SnapStartApplyOn::exists((string) $json['ApplyOn']) ? SnapStartApplyOn::UNKNOWN_TO_SDK : (string) $json['ApplyOn']) : null,
            'OptimizationStatus' => isset($json['OptimizationStatus']) ? (!SnapStartOptimizationStatus::exists((string) $json['OptimizationStatus']) ? SnapStartOptimizationStatus::UNKNOWN_TO_SDK : (string) $json['OptimizationStatus']) : null,
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

    private function populateResultTenancyConfig(array $json): TenancyConfig
    {
        return new TenancyConfig([
            'TenantIsolationMode' => !TenantIsolationMode::exists((string) $json['TenantIsolationMode']) ? TenantIsolationMode::UNKNOWN_TO_SDK : (string) $json['TenantIsolationMode'],
        ]);
    }

    private function populateResultTracingConfigResponse(array $json): TracingConfigResponse
    {
        return new TracingConfigResponse([
            'Mode' => isset($json['Mode']) ? (!TracingMode::exists((string) $json['Mode']) ? TracingMode::UNKNOWN_TO_SDK : (string) $json['Mode']) : null,
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
