<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Input;

use AsyncAws\CodeDeploy\Enum\AutoRollbackEvent;
use AsyncAws\CodeDeploy\Enum\BundleType;
use AsyncAws\CodeDeploy\Enum\EC2TagFilterType;
use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;
use AsyncAws\CodeDeploy\Enum\RevisionLocationType;
use AsyncAws\CodeDeploy\Input\CreateDeploymentInput;
use AsyncAws\CodeDeploy\ValueObject\AppSpecContent;
use AsyncAws\CodeDeploy\ValueObject\AutoRollbackConfiguration;
use AsyncAws\CodeDeploy\ValueObject\EC2TagFilter;
use AsyncAws\CodeDeploy\ValueObject\EC2TagSet;
use AsyncAws\CodeDeploy\ValueObject\GitHubLocation;
use AsyncAws\CodeDeploy\ValueObject\RawString;
use AsyncAws\CodeDeploy\ValueObject\RevisionLocation;
use AsyncAws\CodeDeploy\ValueObject\S3Location;
use AsyncAws\CodeDeploy\ValueObject\TargetInstances;
use AsyncAws\Core\Test\TestCase;

class CreateDeploymentInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateDeploymentInput([
            'applicationName' => 'application-name',
            'deploymentGroupName' => 'deployment-group-name',
            'revision' => new RevisionLocation([
                'revisionType' => RevisionLocationType::GIT_HUB,
                's3Location' => new S3Location([
                    'bucket' => 'bucket',
                    'key' => 'key',
                    'bundleType' => BundleType::JSON,
                    'version' => 'version',
                    'eTag' => 'e-tag',
                ]),
                'gitHubLocation' => new GitHubLocation([
                    'repository' => 'repository',
                    'commitId' => 'commit-id',
                ]),
                'string' => new RawString([
                    'content' => 'content',
                    'sha256' => 'sha256',
                ]),
                'appSpecContent' => new AppSpecContent([
                    'content' => 'content',
                    'sha256' => 'sha256',
                ]),
            ]),
            'deploymentConfigName' => 'deployment-config-name',
            'description' => 'description',
            'ignoreApplicationStopFailures' => false,
            'targetInstances' => new TargetInstances([
                'tagFilters' => [new EC2TagFilter([
                    'Key' => 'key',
                    'Value' => 'valu3',
                    'Type' => EC2TagFilterType::KEY_AND_VALUE,
                ])],
                'autoScalingGroups' => ['auto-scaling-groups'],
                'ec2TagSet' => new EC2TagSet([
                    'ec2TagSetList' => [[new EC2TagFilter([
                        'Key' => 'key',
                        'Value' => 'value',
                        'Type' => EC2TagFilterType::KEY_AND_VALUE,
                    ])]],
                ]),
            ]),
            'autoRollbackConfiguration' => new AutoRollbackConfiguration([
                'enabled' => false,
                'events' => [AutoRollbackEvent::DEPLOYMENT_FAILURE],
            ]),
            'updateOutdatedInstancesOnly' => false,
            'fileExistsBehavior' => FileExistsBehavior::DISALLOW,
        ]);

        // see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_CreateDeployment.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: CodeDeploy_20141006.CreateDeployment

{
    "applicationName": "application-name",
    "autoRollbackConfiguration": {
        "enabled": false,
        "events": [
            "DEPLOYMENT_FAILURE"
        ]
    },
    "deploymentConfigName": "deployment-config-name",
    "deploymentGroupName": "deployment-group-name",
    "description": "description",
    "fileExistsBehavior": "DISALLOW",
    "ignoreApplicationStopFailures": false,
    "revision": {
        "appSpecContent": {
            "content": "content",
            "sha256": "sha256"
        },
        "gitHubLocation": {
            "commitId": "commit-id",
            "repository": "repository"
        },
        "revisionType": "GitHub",
        "s3Location": {
            "bucket": "bucket",
            "bundleType": "JSON",
            "eTag": "e-tag",
            "key": "key",
            "version": "version"
        },
        "string": {
            "content": "content",
            "sha256": "sha256"
        }
    },
    "targetInstances": {
        "autoScalingGroups": [
            "auto-scaling-groups"
        ],
        "ec2TagSet": {
            "ec2TagSetList": [
                [
                    {
                        "Key": "key",
                        "Type": "KEY_AND_VALUE",
                        "Value": "value"
                    }
                ]
            ]
        },
        "tagFilters": [
            {
                "Key": "key",
                "Type": "KEY_AND_VALUE",
                "Value": "valu3"
            }
        ]
    },
    "updateOutdatedInstancesOnly": false
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
