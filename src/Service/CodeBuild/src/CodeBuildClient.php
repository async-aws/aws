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
     *   @region?: string,
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
     * Starts running a build.
     *
     * @see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_StartBuild.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codebuild-2016-10-06.html#startbuild
     *
     * @param array{
     *   projectName: string,
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
     *   @region?: string,
     * }|StartBuildInput $input
     *
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws AccountLimitExceededException
     */
    public function startBuild($input): StartBuildOutput
    {
        $input = StartBuildInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartBuild', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidInputException' => InvalidInputException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'AccountLimitExceededException' => AccountLimitExceededException::class,
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
     *   @region?: string,
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
        }

        return [
            'endpoint' => "https://codebuild.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'codebuild',
            'signVersions' => ['v4'],
        ];
    }
}
