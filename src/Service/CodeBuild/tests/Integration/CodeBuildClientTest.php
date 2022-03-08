<?php

namespace AsyncAws\CodeBuild\Tests\Integration;

use AsyncAws\CodeBuild\CodeBuildClient;
use AsyncAws\CodeBuild\Input\BatchGetBuildsInput;
use AsyncAws\CodeBuild\Input\StartBuildInput;
use AsyncAws\CodeBuild\Input\StopBuildInput;
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
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CodeBuildClientTest extends TestCase
{
    public function testBatchGetBuilds(): void
    {
        $client = $this->getClient();

        $input = new BatchGetBuildsInput([
            'ids' => ['change me'],
        ]);
        $result = $client->batchGetBuilds($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getbuilds());
        // self::assertTODO(expected, $result->getbuildsNotFound());
    }

    public function testStartBuild(): void
    {
        $client = $this->getClient();

        $input = new StartBuildInput([
            'projectName' => 'change me',
            'secondarySourcesOverride' => [new ProjectSource([
                'type' => 'change me',
                'location' => 'change me',
                'gitCloneDepth' => 1337,
                'gitSubmodulesConfig' => new GitSubmodulesConfig([
                    'fetchSubmodules' => false,
                ]),
                'buildspec' => 'change me',
                'auth' => new SourceAuth([
                    'type' => 'change me',
                    'resource' => 'change me',
                ]),
                'reportBuildStatus' => false,
                'buildStatusConfig' => new BuildStatusConfig([
                    'context' => 'change me',
                    'targetUrl' => 'change me',
                ]),
                'insecureSsl' => false,
                'sourceIdentifier' => 'change me',
            ])],
            'secondarySourcesVersionOverride' => [new ProjectSourceVersion([
                'sourceIdentifier' => 'change me',
                'sourceVersion' => 'change me',
            ])],
            'sourceVersion' => 'change me',
            'artifactsOverride' => new ProjectArtifacts([
                'type' => 'change me',
                'location' => 'change me',
                'path' => 'change me',
                'namespaceType' => 'change me',
                'name' => 'change me',
                'packaging' => 'change me',
                'overrideArtifactName' => false,
                'encryptionDisabled' => false,
                'artifactIdentifier' => 'change me',
                'bucketOwnerAccess' => 'change me',
            ]),
            'secondaryArtifactsOverride' => [new ProjectArtifacts([
                'type' => 'change me',
                'location' => 'change me',
                'path' => 'change me',
                'namespaceType' => 'change me',
                'name' => 'change me',
                'packaging' => 'change me',
                'overrideArtifactName' => false,
                'encryptionDisabled' => false,
                'artifactIdentifier' => 'change me',
                'bucketOwnerAccess' => 'change me',
            ])],
            'environmentVariablesOverride' => [new EnvironmentVariable([
                'name' => 'change me',
                'value' => 'change me',
                'type' => 'change me',
            ])],
            'sourceTypeOverride' => 'change me',
            'sourceLocationOverride' => 'change me',
            'sourceAuthOverride' => new SourceAuth([
                'type' => 'change me',
                'resource' => 'change me',
            ]),
            'gitCloneDepthOverride' => 1337,
            'gitSubmodulesConfigOverride' => new GitSubmodulesConfig([
                'fetchSubmodules' => false,
            ]),
            'buildspecOverride' => 'change me',
            'insecureSslOverride' => false,
            'reportBuildStatusOverride' => false,
            'buildStatusConfigOverride' => new BuildStatusConfig([
                'context' => 'change me',
                'targetUrl' => 'change me',
            ]),
            'environmentTypeOverride' => 'change me',
            'imageOverride' => 'change me',
            'computeTypeOverride' => 'change me',
            'certificateOverride' => 'change me',
            'cacheOverride' => new ProjectCache([
                'type' => 'change me',
                'location' => 'change me',
                'modes' => ['change me'],
            ]),
            'serviceRoleOverride' => 'change me',
            'privilegedModeOverride' => false,
            'timeoutInMinutesOverride' => 1337,
            'queuedTimeoutInMinutesOverride' => 1337,
            'encryptionKeyOverride' => 'change me',
            'idempotencyToken' => 'change me',
            'logsConfigOverride' => new LogsConfig([
                'cloudWatchLogs' => new CloudWatchLogsConfig([
                    'status' => 'change me',
                    'groupName' => 'change me',
                    'streamName' => 'change me',
                ]),
                's3Logs' => new S3LogsConfig([
                    'status' => 'change me',
                    'location' => 'change me',
                    'encryptionDisabled' => false,
                    'bucketOwnerAccess' => 'change me',
                ]),
            ]),
            'registryCredentialOverride' => new RegistryCredential([
                'credential' => 'change me',
                'credentialProvider' => 'change me',
            ]),
            'imagePullCredentialsTypeOverride' => 'change me',
            'debugSessionEnabled' => false,
        ]);
        $result = $client->startBuild($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getbuild());
    }

    public function testStopBuild(): void
    {
        $client = $this->getClient();

        $input = new StopBuildInput([
            'id' => 'change me',
        ]);
        $result = $client->stopBuild($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getbuild());
    }

    private function getClient(): CodeBuildClient
    {
        self::markTestSkipped('There is no docker image available for CodeDeploy.');

        return new CodeBuildClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
