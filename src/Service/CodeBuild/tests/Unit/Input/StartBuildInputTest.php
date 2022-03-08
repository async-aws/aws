<?php

namespace AsyncAws\CodeBuild\Tests\Unit\Input;

use AsyncAws\CodeBuild\Enum\ArtifactNamespace;
use AsyncAws\CodeBuild\Enum\ArtifactPackaging;
use AsyncAws\CodeBuild\Enum\ArtifactsType;
use AsyncAws\CodeBuild\Enum\BucketOwnerAccess;
use AsyncAws\CodeBuild\Enum\CacheMode;
use AsyncAws\CodeBuild\Enum\CacheType;
use AsyncAws\CodeBuild\Enum\ComputeType;
use AsyncAws\CodeBuild\Enum\CredentialProviderType;
use AsyncAws\CodeBuild\Enum\EnvironmentType;
use AsyncAws\CodeBuild\Enum\EnvironmentVariableType;
use AsyncAws\CodeBuild\Enum\ImagePullCredentialsType;
use AsyncAws\CodeBuild\Enum\LogsConfigStatusType;
use AsyncAws\CodeBuild\Enum\SourceAuthType;
use AsyncAws\CodeBuild\Enum\SourceType;
use AsyncAws\CodeBuild\Input\StartBuildInput;
use AsyncAws\CodeBuild\ValueObject\BuildStatusConfig;
use AsyncAws\CodeBuild\ValueObject\CloudWatchLogsConfig;
use AsyncAws\CodeBuild\ValueObject\EnvironmentVariable;
use AsyncAws\CodeBuild\ValueObject\GitSubmodulesConfig;
use AsyncAws\CodeBuild\ValueObject\LogsConfig;
use AsyncAws\CodeBuild\ValueObject\ProjectArtifacts;
use AsyncAws\CodeBuild\ValueObject\ProjectCache;
use AsyncAws\CodeBuild\ValueObject\ProjectSource;
use AsyncAws\CodeBuild\ValueObject\ProjectSourceVersion;
use AsyncAws\CodeBuild\ValueObject\RegistryCredential;
use AsyncAws\CodeBuild\ValueObject\S3LogsConfig;
use AsyncAws\CodeBuild\ValueObject\SourceAuth;
use AsyncAws\Core\Test\TestCase;

class StartBuildInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartBuildInput([
            'projectName' => 'the project',
            'secondarySourcesOverride' => [new ProjectSource([
                'type' => SourceType::BITBUCKET,
                'location' => 'https://bitbucket.com',
                'gitCloneDepth' => 5,
                'gitSubmodulesConfig' => new GitSubmodulesConfig([
                    'fetchSubmodules' => false,
                ]),
                'buildspec' => 'YAML SPEC',
                'auth' => new SourceAuth([
                    'type' => SourceAuthType::OAUTH,
                    'resource' => 'auth-resource',
                ]),
                'reportBuildStatus' => false,
                'buildStatusConfig' => new BuildStatusConfig([
                    'context' => 'prod',
                    'targetUrl' => 'http://target',
                ]),
                'insecureSsl' => false,
                'sourceIdentifier' => 'source-id',
            ])],
            'secondarySourcesVersionOverride' => [new ProjectSourceVersion([
                'sourceIdentifier' => 'source-id',
                'sourceVersion' => '1.2.3',
            ])],
            'sourceVersion' => '1.2',
            'artifactsOverride' => new ProjectArtifacts([
                'type' => ArtifactsType::S3,
                'location' => 'http://artifact.s3',
                'path' => '/path/to/artifacts',
                'namespaceType' => ArtifactNamespace::BUILD_ID,
                'name' => 'artifact',
                'packaging' => ArtifactPackaging::ZIP,
                'overrideArtifactName' => false,
                'encryptionDisabled' => false,
                'artifactIdentifier' => 'output',
                'bucketOwnerAccess' => BucketOwnerAccess::FULL,
            ]),
            'secondaryArtifactsOverride' => [new ProjectArtifacts([
                'type' => ArtifactsType::S3,
                'location' => 'http://artifact.s3',
                'path' => '/second-path',
                'namespaceType' => ArtifactNamespace::BUILD_ID,
                'name' => 'stderr',
                'packaging' => ArtifactPackaging::ZIP,
                'overrideArtifactName' => false,
                'encryptionDisabled' => false,
                'artifactIdentifier' => 'err',
                'bucketOwnerAccess' => BucketOwnerAccess::FULL,
            ])],
            'environmentVariablesOverride' => [new EnvironmentVariable([
                'name' => 'APP_ENV',
                'value' => 'prod',
                'type' => EnvironmentVariableType::PLAINTEXT,
            ])],
            'sourceTypeOverride' => SourceType::S3,
            'sourceLocationOverride' => '/path-to-source',
            'sourceAuthOverride' => new SourceAuth([
                'type' => SourceAuthType::OAUTH,
                'resource' => 'auth-resource',
            ]),
            'gitCloneDepthOverride' => 6,
            'gitSubmodulesConfigOverride' => new GitSubmodulesConfig([
                'fetchSubmodules' => false,
            ]),
            'buildspecOverride' => 'YAML specs',
            'insecureSslOverride' => false,
            'reportBuildStatusOverride' => false,
            'buildStatusConfigOverride' => new BuildStatusConfig([
                'context' => 'context',
                'targetUrl' => 'http://target',
            ]),
            'environmentTypeOverride' => EnvironmentType::ARM_CONTAINER,
            'imageOverride' => 'base-image',
            'computeTypeOverride' => ComputeType::BUILD_GENERAL1_2XLARGE,
            'certificateOverride' => 'PRIVATE-CERT',
            'cacheOverride' => new ProjectCache([
                'type' => CacheType::S3,
                'location' => '/path',
                'modes' => [CacheMode::LOCAL_CUSTOM_CACHE],
            ]),
            'serviceRoleOverride' => 'demo-build',
            'privilegedModeOverride' => false,
            'timeoutInMinutesOverride' => 60,
            'queuedTimeoutInMinutesOverride' => 30,
            'encryptionKeyOverride' => 'the-key',
            'idempotencyToken' => 'token',
            'logsConfigOverride' => new LogsConfig([
                'cloudWatchLogs' => new CloudWatchLogsConfig([
                    'status' => LogsConfigStatusType::ENABLED,
                    'groupName' => 'logs',
                    'streamName' => 'log-build',
                ]),
                's3Logs' => new S3LogsConfig([
                    'status' => LogsConfigStatusType::ENABLED,
                    'location' => '/path-to-log',
                    'encryptionDisabled' => false,
                    'bucketOwnerAccess' => BucketOwnerAccess::FULL,
                ]),
            ]),
            'registryCredentialOverride' => new RegistryCredential([
                'credential' => 'this-is-secret',
                'credentialProvider' => CredentialProviderType::SECRETS_MANAGER,
            ]),
            'imagePullCredentialsTypeOverride' => ImagePullCredentialsType::CODEBUILD,
            'debugSessionEnabled' => false,
        ]);

        // see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_StartBuild.html
        $expected = '
        POST / HTTP/1.0
        Content-Type: application/x-amz-json-1.1
        x-amz-target: CodeBuild_20161006.StartBuild

        {
            "artifactsOverride": {
                "artifactIdentifier": "output",
                "bucketOwnerAccess": "FULL",
                "encryptionDisabled": false,
                "location": "http://artifact.s3",
                "name": "artifact",
                "namespaceType": "BUILD_ID",
                "overrideArtifactName": false,
                "packaging": "ZIP",
                "path": "/path/to/artifacts",
                "type": "S3"
            },
            "buildStatusConfigOverride": {
                "context": "context",
                "targetUrl": "http://target"
            },
            "buildspecOverride": "YAML specs",
            "cacheOverride": {
                "location": "/path",
                "modes": [
                    "LOCAL_CUSTOM_CACHE"
                ],
                "type": "S3"
            },
            "certificateOverride": "PRIVATE-CERT",
            "computeTypeOverride": "BUILD_GENERAL1_2XLARGE",
            "debugSessionEnabled": false,
            "encryptionKeyOverride": "the-key",
            "environmentTypeOverride": "ARM_CONTAINER",
            "environmentVariablesOverride": [
                {
                    "name": "APP_ENV",
                    "type": "PLAINTEXT",
                    "value": "prod"
                }
            ],
            "gitCloneDepthOverride": 6,
            "gitSubmodulesConfigOverride": {
                "fetchSubmodules": false
            },
             "idempotencyToken": "token",
            "idempotencyToken": "token",
            "imageOverride": "base-image",
            "imagePullCredentialsTypeOverride": "CODEBUILD",
            "insecureSslOverride": false,
            "logsConfigOverride": {
                "cloudWatchLogs": {
                    "groupName": "logs",
                    "status": "ENABLED",
                    "streamName": "log-build"
                },
                "s3Logs": {
                    "bucketOwnerAccess": "FULL",
                    "encryptionDisabled": false,
                    "location": "/path-to-log",
                    "status": "ENABLED"
                }
            },
            "privilegedModeOverride": false,
            "projectName": "the project",
            "queuedTimeoutInMinutesOverride": 30,
            "registryCredentialOverride": {
                "credential": "this-is-secret",
                "credentialProvider": "SECRETS_MANAGER"
            },
            "reportBuildStatusOverride": false,
            "secondaryArtifactsOverride": [
                {
                    "artifactIdentifier": "err",
                    "bucketOwnerAccess": "FULL",
                    "encryptionDisabled": false,
                    "location": "http://artifact.s3",
                    "name": "stderr",
                    "namespaceType": "BUILD_ID",
                    "overrideArtifactName": false,
                    "packaging": "ZIP",
                    "path": "/second-path",
                    "type": "S3"
                }
            ],
            "secondarySourcesOverride": [
                {
                    "auth": {
                        "resource": "auth-resource",
                        "type": "OAUTH"
                    },
                    "buildStatusConfig": {
                        "context": "prod",
                        "targetUrl": "http://target"
                    },
                    "buildspec": "YAML SPEC",
                    "gitCloneDepth": 5,
                    "gitSubmodulesConfig": {
                        "fetchSubmodules": false
                    },
                    "insecureSsl": false,
                    "location": "https://bitbucket.com",
                    "reportBuildStatus": false,
                    "sourceIdentifier": "source-id",
                    "type": "BITBUCKET"
                }
            ],
            "secondarySourcesVersionOverride": [
                {
                    "sourceIdentifier": "source-id",
                    "sourceVersion": "1.2.3"
                }
            ],
            "serviceRoleOverride": "demo-build",
            "sourceAuthOverride": {
                "resource": "auth-resource",
                "type": "OAUTH"
            },
            "sourceLocationOverride": "/path-to-source",
            "sourceTypeOverride": "S3",
            "sourceVersion": "1.2",
            "timeoutInMinutesOverride": 60
        }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
