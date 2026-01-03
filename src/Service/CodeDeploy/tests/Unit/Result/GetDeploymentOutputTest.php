<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Result;

use AsyncAws\CodeDeploy\Enum\AutoRollbackEvent;
use AsyncAws\CodeDeploy\Enum\BundleType;
use AsyncAws\CodeDeploy\Enum\ComputePlatform;
use AsyncAws\CodeDeploy\Enum\DeploymentOption;
use AsyncAws\CodeDeploy\Enum\DeploymentReadyAction;
use AsyncAws\CodeDeploy\Enum\DeploymentStatus;
use AsyncAws\CodeDeploy\Enum\DeploymentType;
use AsyncAws\CodeDeploy\Enum\ErrorCode;
use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;
use AsyncAws\CodeDeploy\Enum\GreenFleetProvisioningAction;
use AsyncAws\CodeDeploy\Enum\InstanceAction;
use AsyncAws\CodeDeploy\Enum\RevisionLocationType;
use AsyncAws\CodeDeploy\Result\GetDeploymentOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetDeploymentOutputTest extends TestCase
{
    public function testGetDeploymentOutput(): void
    {
        // see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_GetDeployment.html
        $response = new SimpleMockedResponse('{
            "deploymentInfo": {
                "additionalDeploymentStatusInfo": "additional-deployment-status-info",
                "applicationName": "application-name",
                "autoRollbackConfiguration": {
                    "enabled": true,
                    "events": ["DEPLOYMENT_FAILURE"]
                },
                "blueGreenDeploymentConfiguration": {
                    "deploymentReadyOption": {
                        "actionOnTimeout": "CONTINUE_DEPLOYMENT",
                        "waitTimeInMinutes": 1
                    },
                    "greenFleetProvisioningOption": {
                        "action": "DISCOVER_EXISTING"
                    },
                    "terminateBlueInstancesOnDeploymentSuccess": {
                        "action": "TERMINATE",
                        "terminationWaitTimeInMinutes": 2
                    }
                },
                "completeTime": 3,
                "computePlatform": "Server",
                "createTime": 4,
                "creator": "CodeDeploy",
                "deploymentConfigName": "deployment-config-name",
                "deploymentGroupName": "deployment-group-name",
                "deploymentId": "123",
                "deploymentOverview": {
                    "Failed": 1,
                    "InProgress": 2,
                    "Pending": 3,
                    "Ready": 4,
                    "Skipped": 5,
                    "Succeeded": 6
                },
                "deploymentStatusMessages": ["deployment-status-messages"],
                "deploymentStyle": {
                    "deploymentOption": "WITH_TRAFFIC_CONTROL",
                    "deploymentType": "IN_PLACE"
                },
                "description": "description",
                "errorInformation": {
                    "code": "APPLICATION_MISSING",
                    "message": "message"
                },
                "externalId": "external-id",
                "fileExistsBehavior": "DISALLOW",
                "ignoreApplicationStopFailures": true,
                "instanceTerminationWaitTimeStarted": true,
                "loadBalancerInfo": {
                    "elbInfoList": [
                        {
                            "name": "elb-name"
                        }
                    ],
                    "targetGroupInfoList": [
                        {
                            "name": "tg-name"
                        }
                    ],
                    "targetGroupPairInfoList": [
                        {
                            "prodTrafficRoute": {
                                "listenerArns": ["prod-listener-arn"]
                            },
                            "targetGroups": [
                                {
                                    "name": "prod-tg-name"
                                }
                            ],
                            "testTrafficRoute": {
                                "listenerArns": ["listener-arn"]
                            }
                        }
                    ]
                },
                "previousRevision": {
                    "appSpecContent": {
                        "content": "content",
                        "sha256": "sha256"
                    },
                    "gitHubLocation": {
                        "commitId": "commmit-id",
                        "repository": "repository"
                    },
                    "revisionType": "GitHub",
                    "s3Location": {
                        "bucket": "bucket",
                        "bundleType": "tar",
                        "eTag": "e-tag",
                        "key": "key",
                        "version": "version"
                    },
                    "string": {
                        "content": "content",
                        "sha256": "sha256"
                    }
                },
                "relatedDeployments": {
                    "autoUpdateOutdatedInstancesDeploymentIds": ["456"],
                    "autoUpdateOutdatedInstancesRootDeploymentId": "789"
                },
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
                        "bundleType": "tar",
                        "eTag": "e-tag",
                        "key": "key",
                        "version": "version"
                    },
                    "string": {
                        "content": "content",
                        "sha256": "sha256"
                    }
                },
                "rollbackInfo": {
                    "rollbackDeploymentId": "rollback-deployment-id",
                    "rollbackMessage": "rollback-message",
                    "rollbackTriggeringDeploymentId": "rollback-triggering-deployment-id"
                },
                "startTime": 321,
                "status": "Created",
                "targetInstances": {
                    "autoScalingGroups":["asg1"],
                    "ec2TagSet": {
                        "ec2TagSetList": [
                            [
                                {
                                    "Key": "key",
                                    "Type": "KEY_ONLY",
                                    "Value": "value"
                                }
                            ]
                        ]
                    },
                    "tagFilters": [
                        {
                            "Key": "key",
                            "Type": "KEY_ONLY",
                            "Value": "value"
                        }
                    ]
                },
                "updateOutdatedInstancesOnly": true
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetDeploymentOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $info = $result->getdeploymentInfo();

        self::assertSame('additional-deployment-status-info', $info->getAdditionalDeploymentStatusInfo());
        self::assertSame('application-name', $info->getApplicationName());
        self::assertEquals(new \DateTimeImmutable('1970-01-01 00:00:03.000000'), $info->getCompleteTime());
        self::assertSame(ComputePlatform::SERVER, $info->getComputePlatform());
        self::assertEquals(new \DateTimeImmutable('1970-01-01 00:00:04.000000'), $info->getCreateTime());
        self::assertSame('CodeDeploy', $info->getCreator());
        self::assertSame('deployment-config-name', $info->getDeploymentConfigName());
        self::assertSame('deployment-group-name', $info->getDeploymentGroupName());
        self::assertSame('123', $info->getDeploymentId());
        self::assertSame(['deployment-status-messages'], $info->getDeploymentStatusMessages());
        self::assertSame('description', $info->getDescription());
        self::assertSame('external-id', $info->getExternalId());
        self::assertSame(FileExistsBehavior::DISALLOW, $info->getFileExistsBehavior());
        self::assertSame(true, $info->getIgnoreApplicationStopFailures());
        self::assertSame(true, $info->getInstanceTerminationWaitTimeStarted());
        self::assertEquals(new \DateTimeImmutable('1970-01-01 00:05:21.000000'), $info->getStartTime());
        self::assertSame(DeploymentStatus::CREATED, $info->getStatus());
        self::assertSame(true, $info->getUpdateOutdatedInstancesOnly());

        self::assertSame(true, $info->getAutoRollbackConfiguration()->getEnabled());
        self::assertSame([AutoRollbackEvent::DEPLOYMENT_FAILURE], $info->getAutoRollbackConfiguration()->getEvents());

        self::assertSame(DeploymentReadyAction::CONTINUE_DEPLOYMENT, $info->getBlueGreenDeploymentConfiguration()->getDeploymentReadyOption()->getActionOnTimeout());
        self::assertSame(1, $info->getBlueGreenDeploymentConfiguration()->getDeploymentReadyOption()->getWaitTimeInMinutes());
        self::assertSame(GreenFleetProvisioningAction::DISCOVER_EXISTING, $info->getBlueGreenDeploymentConfiguration()->getGreenFleetProvisioningOption()->getAction());
        self::assertSame(InstanceAction::TERMINATE, $info->getBlueGreenDeploymentConfiguration()->getTerminateBlueInstancesOnDeploymentSuccess()->getAction());
        self::assertSame(2, $info->getBlueGreenDeploymentConfiguration()->getTerminateBlueInstancesOnDeploymentSuccess()->getTerminationWaitTimeInMinutes());

        self::assertSame(1, $info->getDeploymentOverview()->getFailed());
        self::assertSame(2, $info->getDeploymentOverview()->getInProgress());
        self::assertSame(3, $info->getDeploymentOverview()->getPending());
        self::assertSame(4, $info->getDeploymentOverview()->getReady());
        self::assertSame(5, $info->getDeploymentOverview()->getSkipped());
        self::assertSame(6, $info->getDeploymentOverview()->getSucceeded());

        self::assertSame(DeploymentOption::WITH_TRAFFIC_CONTROL, $info->getDeploymentStyle()->getDeploymentOption());
        self::assertSame(DeploymentType::IN_PLACE, $info->getDeploymentStyle()->getDeploymentType());

        self::assertSame(ErrorCode::APPLICATION_MISSING, $info->getErrorInformation()->getCode());
        self::assertSame('message', $info->getErrorInformation()->getMessage());

        self::assertSame('elb-name', $info->getLoadBalancerInfo()->getElbInfoList()[0]->getName());
        self::assertSame('tg-name', $info->getLoadBalancerInfo()->getTargetGroupInfoList()[0]->getName());

        self::assertEquals(['prod-listener-arn'], $info->getLoadBalancerInfo()->getTargetGroupPairInfoList()[0]->getProdTrafficRoute()->getListenerArns());
        self::assertEquals('prod-tg-name', $info->getLoadBalancerInfo()->getTargetGroupPairInfoList()[0]->getTargetGroups()[0]->getName());
        self::assertEquals(['listener-arn'], $info->getLoadBalancerInfo()->getTargetGroupPairInfoList()[0]->getTestTrafficRoute()->getListenerArns());

        self::assertSame('content', $info->getPreviousRevision()->getAppSpecContent()->getContent());
        self::assertSame('sha256', $info->getPreviousRevision()->getAppSpecContent()->getSha256());

        self::assertSame('commmit-id', $info->getPreviousRevision()->getGitHubLocation()->getCommitId());
        self::assertSame('repository', $info->getPreviousRevision()->getGitHubLocation()->getRepository());

        self::assertSame(RevisionLocationType::GIT_HUB, $info->getPreviousRevision()->getRevisionType());

        self::assertSame('bucket', $info->getPreviousRevision()->getS3Location()->getBucket());
        self::assertSame(BundleType::TAR, $info->getPreviousRevision()->getS3Location()->getBundleType());
        self::assertSame('e-tag', $info->getPreviousRevision()->getS3Location()->getETag());
        self::assertSame('key', $info->getPreviousRevision()->getS3Location()->getKey());
        self::assertSame('version', $info->getPreviousRevision()->getS3Location()->getVersion());

        self::assertSame(['456'], $info->getRelatedDeployments()->getAutoUpdateOutdatedInstancesDeploymentIds());
        self::assertSame('789', $info->getRelatedDeployments()->getAutoUpdateOutdatedInstancesRootDeploymentId());

        self::assertSame('content', $info->getRevision()->getAppSpecContent()->getContent());
        self::assertSame('sha256', $info->getRevision()->getAppSpecContent()->getSha256());

        self::assertSame('commit-id', $info->getRevision()->getGitHubLocation()->getCommitId());
        self::assertSame('repository', $info->getRevision()->getGitHubLocation()->getRepository());

        self::assertSame(RevisionLocationType::GIT_HUB, $info->getRevision()->getRevisionType());

        self::assertSame('bucket', $info->getRevision()->getS3Location()->getBucket());
        self::assertSame(BundleType::TAR, $info->getRevision()->getS3Location()->getBundleType());
        self::assertSame('e-tag', $info->getRevision()->getS3Location()->getETag());
        self::assertSame('key', $info->getRevision()->getS3Location()->getKey());
        self::assertSame('version', $info->getRevision()->getS3Location()->getVersion());

        self::assertSame('rollback-deployment-id', $info->getRollbackInfo()->getRollbackDeploymentId());
        self::assertSame('rollback-message', $info->getRollbackInfo()->getRollbackMessage());
        self::assertSame('rollback-triggering-deployment-id', $info->getRollbackInfo()->getRollbackTriggeringDeploymentId());

        self::assertSame(['asg1'], $info->getTargetInstances()->getAutoScalingGroups());
        self::assertSame('key', $info->getTargetInstances()->getEc2TagSet()->getEc2TagSetList()[0][0]->getKey());
        self::assertSame('KEY_ONLY', $info->getTargetInstances()->getEc2TagSet()->getEc2TagSetList()[0][0]->getType());
        self::assertSame('value', $info->getTargetInstances()->getEc2TagSet()->getEc2TagSetList()[0][0]->getValue());

        self::assertSame('key', $info->getTargetInstances()->getTagFilters()[0]->getKey());
        self::assertSame('KEY_ONLY', $info->getTargetInstances()->getTagFilters()[0]->getType());
        self::assertSame('value', $info->getTargetInstances()->getTagFilters()[0]->getValue());
    }
}
