<?php

namespace AsyncAws\CodeBuild;

use AsyncAws\CodeBuild\Enum\ComputeType;
use AsyncAws\CodeBuild\Enum\EnvironmentType;
use AsyncAws\CodeBuild\Enum\ImagePullCredentialsType;
use AsyncAws\CodeBuild\Enum\SourceType;
use AsyncAws\CodeBuild\Exception\AccountLimitExceededException;
use AsyncAws\CodeBuild\Exception\InvalidInputException;
use AsyncAws\CodeBuild\Exception\ResourceNotFoundException;
use AsyncAws\CodeBuild\Input\BatchGetBuildsInput;
use AsyncAws\CodeBuild\Input\StartBuildInput;
use AsyncAws\CodeBuild\Input\StopBuildInput;
use AsyncAws\CodeBuild\Result\BatchGetBuildsOutput;
use AsyncAws\CodeBuild\Result\StartBuildOutput;
use AsyncAws\CodeBuild\Result\StopBuildOutput;
use AsyncAws\CodeBuild\ValueObject\BuildStatusConfig;
use AsyncAws\CodeBuild\ValueObject\EnvironmentVariable;
use AsyncAws\CodeBuild\ValueObject\GitSubmodulesConfig;
use AsyncAws\CodeBuild\ValueObject\LogsConfig;
use AsyncAws\CodeBuild\ValueObject\ProjectArtifacts;
use AsyncAws\CodeBuild\ValueObject\ProjectCache;
use AsyncAws\CodeBuild\ValueObject\ProjectFleet;
use AsyncAws\CodeBuild\ValueObject\ProjectSource;
use AsyncAws\CodeBuild\ValueObject\ProjectSourceVersion;
use AsyncAws\CodeBuild\ValueObject\RegistryCredential;
use AsyncAws\CodeBuild\ValueObject\SourceAuth;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class CodeBuildClient extends AbstractApi
{
    /**
     * Gets information about one or more builds.
     *
     * @see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_BatchGetBuilds.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codebuild-2016-10-06.html#batchgetbuilds
     *
     * @param array{
     *   ids: string[],
     *   '@region'?: string|null,
     * }|BatchGetBuildsInput $input
     *
     * @throws InvalidInputException
     */
    public function batchGetBuilds($input): BatchGetBuildsOutput
    {
        $input = BatchGetBuildsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchGetBuilds', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInputException' => InvalidInputException::class,
        ]]));

        return new BatchGetBuildsOutput($response);
    }

    /**
     * Starts running a build with the settings defined in the project. These setting include: how to run a build, where to
     * get the source code, which build environment to use, which build commands to run, and where to store the build
     * output.
     *
     * You can also start a build run by overriding some of the build settings in the project. The overrides only apply for
     * that specific start build request. The settings in the project are unaltered.
     *
     * @see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_StartBuild.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codebuild-2016-10-06.html#startbuild
     *
     * @param array{
     *   projectName: string,
     *   secondarySourcesOverride?: array<ProjectSource|array>|null,
     *   secondarySourcesVersionOverride?: array<ProjectSourceVersion|array>|null,
     *   sourceVersion?: string|null,
     *   artifactsOverride?: ProjectArtifacts|array|null,
     *   secondaryArtifactsOverride?: array<ProjectArtifacts|array>|null,
     *   environmentVariablesOverride?: array<EnvironmentVariable|array>|null,
     *   sourceTypeOverride?: SourceType::*|null,
     *   sourceLocationOverride?: string|null,
     *   sourceAuthOverride?: SourceAuth|array|null,
     *   gitCloneDepthOverride?: int|null,
     *   gitSubmodulesConfigOverride?: GitSubmodulesConfig|array|null,
     *   buildspecOverride?: string|null,
     *   insecureSslOverride?: bool|null,
     *   reportBuildStatusOverride?: bool|null,
     *   buildStatusConfigOverride?: BuildStatusConfig|array|null,
     *   environmentTypeOverride?: EnvironmentType::*|null,
     *   imageOverride?: string|null,
     *   computeTypeOverride?: ComputeType::*|null,
     *   certificateOverride?: string|null,
     *   cacheOverride?: ProjectCache|array|null,
     *   serviceRoleOverride?: string|null,
     *   privilegedModeOverride?: bool|null,
     *   timeoutInMinutesOverride?: int|null,
     *   queuedTimeoutInMinutesOverride?: int|null,
     *   encryptionKeyOverride?: string|null,
     *   idempotencyToken?: string|null,
     *   logsConfigOverride?: LogsConfig|array|null,
     *   registryCredentialOverride?: RegistryCredential|array|null,
     *   imagePullCredentialsTypeOverride?: ImagePullCredentialsType::*|null,
     *   debugSessionEnabled?: bool|null,
     *   fleetOverride?: ProjectFleet|array|null,
     *   autoRetryLimitOverride?: int|null,
     *   '@region'?: string|null,
     * }|StartBuildInput $input
     *
     * @throws AccountLimitExceededException
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     */
    public function startBuild($input): StartBuildOutput
    {
        $input = StartBuildInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartBuild', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccountLimitExceededException' => AccountLimitExceededException::class,
            'InvalidInputException' => InvalidInputException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new StartBuildOutput($response);
    }

    /**
     * Attempts to stop running a build.
     *
     * @see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_StopBuild.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codebuild-2016-10-06.html#stopbuild
     *
     * @param array{
     *   id: string,
     *   '@region'?: string|null,
     * }|StopBuildInput $input
     *
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     */
    public function stopBuild($input): StopBuildOutput
    {
        $input = StopBuildInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopBuild', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInputException' => InvalidInputException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new StopBuildOutput($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://codebuild.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://codebuild-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://codebuild-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://codebuild-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://codebuild-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://codebuild-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://codebuild-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://codebuild.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://codebuild.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://codebuild.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'codebuild',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://codebuild.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'codebuild',
            'signVersions' => ['v4'],
        ];
    }
}
