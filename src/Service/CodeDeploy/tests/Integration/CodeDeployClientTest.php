<?php

namespace AsyncAws\CodeDeploy\Tests\Integration;

use AsyncAws\CodeDeploy\CodeDeployClient;
use AsyncAws\CodeDeploy\Input\CreateDeploymentInput;
use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\CodeDeploy\ValueObject\AppSpecContent;
use AsyncAws\CodeDeploy\ValueObject\AutoRollbackConfiguration;
use AsyncAws\CodeDeploy\ValueObject\EC2TagFilter;
use AsyncAws\CodeDeploy\ValueObject\EC2TagSet;
use AsyncAws\CodeDeploy\ValueObject\GitHubLocation;
use AsyncAws\CodeDeploy\ValueObject\RawString;
use AsyncAws\CodeDeploy\ValueObject\RevisionLocation;
use AsyncAws\CodeDeploy\ValueObject\S3Location;
use AsyncAws\CodeDeploy\ValueObject\TargetInstances;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CodeDeployClientTest extends TestCase
{
    public function testCreateDeployment(): void
    {
        $client = $this->getClient();

        $input = new CreateDeploymentInput([
            'applicationName' => 'change me',
            'deploymentGroupName' => 'change me',
            'revision' => new RevisionLocation([
                'revisionType' => 'change me',
                's3Location' => new S3Location([
                    'bucket' => 'change me',
                    'key' => 'change me',
                    'bundleType' => 'change me',
                    'version' => 'change me',
                    'eTag' => 'change me',
                ]),
                'gitHubLocation' => new GitHubLocation([
                    'repository' => 'change me',
                    'commitId' => 'change me',
                ]),
                'string' => new RawString([
                    'content' => 'change me',
                    'sha256' => 'change me',
                ]),
                'appSpecContent' => new AppSpecContent([
                    'content' => 'change me',
                    'sha256' => 'change me',
                ]),
            ]),
            'deploymentConfigName' => 'change me',
            'description' => 'change me',
            'ignoreApplicationStopFailures' => false,
            'targetInstances' => new TargetInstances([
                'tagFilters' => [new EC2TagFilter([
                    'Key' => 'change me',
                    'Value' => 'change me',
                    'Type' => 'change me',
                ])],
                'autoScalingGroups' => ['change me'],
                'ec2TagSet' => new EC2TagSet([
                    'ec2TagSetList' => [[new EC2TagFilter([
                        'Key' => 'change me',
                        'Value' => 'change me',
                        'Type' => 'change me',
                    ])]],
                ]),
            ]),
            'autoRollbackConfiguration' => new AutoRollbackConfiguration([
                'enabled' => false,
                'events' => ['change me'],
            ]),
            'updateOutdatedInstancesOnly' => false,
            'fileExistsBehavior' => 'change me',
        ]);
        $result = $client->createDeployment($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getdeploymentId());
    }

    public function testPutLifecycleEventHookExecutionStatus(): void
    {
        $client = $this->getClient();

        $input = new PutLifecycleEventHookExecutionStatusInput([
            'deploymentId' => 'change me',
            'lifecycleEventHookExecutionId' => 'change me',
            'status' => 'Succeeded',
        ]);
        $result = $client->PutLifecycleEventHookExecutionStatus($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getlifecycleEventHookExecutionId());
    }

    private function getClient(): CodeDeployClient
    {
        self::markTestSkipped('There is no docker image available for CodeDeploy.');

        return new CodeDeployClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
