<?php

namespace AsyncAws\CodeDeploy\Result;

use AsyncAws\CodeDeploy\Enum\AutoRollbackEvent;
use AsyncAws\CodeDeploy\ValueObject\AppSpecContent;
use AsyncAws\CodeDeploy\ValueObject\AutoRollbackConfiguration;
use AsyncAws\CodeDeploy\ValueObject\BlueGreenDeploymentConfiguration;
use AsyncAws\CodeDeploy\ValueObject\BlueInstanceTerminationOption;
use AsyncAws\CodeDeploy\ValueObject\DeploymentInfo;
use AsyncAws\CodeDeploy\ValueObject\DeploymentOverview;
use AsyncAws\CodeDeploy\ValueObject\DeploymentReadyOption;
use AsyncAws\CodeDeploy\ValueObject\DeploymentStyle;
use AsyncAws\CodeDeploy\ValueObject\EC2TagFilter;
use AsyncAws\CodeDeploy\ValueObject\EC2TagSet;
use AsyncAws\CodeDeploy\ValueObject\ELBInfo;
use AsyncAws\CodeDeploy\ValueObject\ErrorInformation;
use AsyncAws\CodeDeploy\ValueObject\GitHubLocation;
use AsyncAws\CodeDeploy\ValueObject\GreenFleetProvisioningOption;
use AsyncAws\CodeDeploy\ValueObject\LoadBalancerInfo;
use AsyncAws\CodeDeploy\ValueObject\RawString;
use AsyncAws\CodeDeploy\ValueObject\RelatedDeployments;
use AsyncAws\CodeDeploy\ValueObject\RevisionLocation;
use AsyncAws\CodeDeploy\ValueObject\RollbackInfo;
use AsyncAws\CodeDeploy\ValueObject\S3Location;
use AsyncAws\CodeDeploy\ValueObject\TargetGroupInfo;
use AsyncAws\CodeDeploy\ValueObject\TargetGroupPairInfo;
use AsyncAws\CodeDeploy\ValueObject\TargetInstances;
use AsyncAws\CodeDeploy\ValueObject\TrafficRoute;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a `GetDeployment` operation.
 */
class GetDeploymentOutput extends Result
{
    /**
     * Information about the deployment.
     */
    private $deploymentInfo;

    public function getDeploymentInfo(): ?DeploymentInfo
    {
        $this->initialize();

        return $this->deploymentInfo;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->deploymentInfo = empty($data['deploymentInfo']) ? null : new DeploymentInfo([
            'applicationName' => isset($data['deploymentInfo']['applicationName']) ? (string) $data['deploymentInfo']['applicationName'] : null,
            'deploymentGroupName' => isset($data['deploymentInfo']['deploymentGroupName']) ? (string) $data['deploymentInfo']['deploymentGroupName'] : null,
            'deploymentConfigName' => isset($data['deploymentInfo']['deploymentConfigName']) ? (string) $data['deploymentInfo']['deploymentConfigName'] : null,
            'deploymentId' => isset($data['deploymentInfo']['deploymentId']) ? (string) $data['deploymentInfo']['deploymentId'] : null,
            'previousRevision' => empty($data['deploymentInfo']['previousRevision']) ? null : new RevisionLocation([
                'revisionType' => isset($data['deploymentInfo']['previousRevision']['revisionType']) ? (string) $data['deploymentInfo']['previousRevision']['revisionType'] : null,
                's3Location' => empty($data['deploymentInfo']['previousRevision']['s3Location']) ? null : new S3Location([
                    'bucket' => isset($data['deploymentInfo']['previousRevision']['s3Location']['bucket']) ? (string) $data['deploymentInfo']['previousRevision']['s3Location']['bucket'] : null,
                    'key' => isset($data['deploymentInfo']['previousRevision']['s3Location']['key']) ? (string) $data['deploymentInfo']['previousRevision']['s3Location']['key'] : null,
                    'bundleType' => isset($data['deploymentInfo']['previousRevision']['s3Location']['bundleType']) ? (string) $data['deploymentInfo']['previousRevision']['s3Location']['bundleType'] : null,
                    'version' => isset($data['deploymentInfo']['previousRevision']['s3Location']['version']) ? (string) $data['deploymentInfo']['previousRevision']['s3Location']['version'] : null,
                    'eTag' => isset($data['deploymentInfo']['previousRevision']['s3Location']['eTag']) ? (string) $data['deploymentInfo']['previousRevision']['s3Location']['eTag'] : null,
                ]),
                'gitHubLocation' => empty($data['deploymentInfo']['previousRevision']['gitHubLocation']) ? null : new GitHubLocation([
                    'repository' => isset($data['deploymentInfo']['previousRevision']['gitHubLocation']['repository']) ? (string) $data['deploymentInfo']['previousRevision']['gitHubLocation']['repository'] : null,
                    'commitId' => isset($data['deploymentInfo']['previousRevision']['gitHubLocation']['commitId']) ? (string) $data['deploymentInfo']['previousRevision']['gitHubLocation']['commitId'] : null,
                ]),
                'string' => empty($data['deploymentInfo']['previousRevision']['string']) ? null : new RawString([
                    'content' => isset($data['deploymentInfo']['previousRevision']['string']['content']) ? (string) $data['deploymentInfo']['previousRevision']['string']['content'] : null,
                    'sha256' => isset($data['deploymentInfo']['previousRevision']['string']['sha256']) ? (string) $data['deploymentInfo']['previousRevision']['string']['sha256'] : null,
                ]),
                'appSpecContent' => empty($data['deploymentInfo']['previousRevision']['appSpecContent']) ? null : new AppSpecContent([
                    'content' => isset($data['deploymentInfo']['previousRevision']['appSpecContent']['content']) ? (string) $data['deploymentInfo']['previousRevision']['appSpecContent']['content'] : null,
                    'sha256' => isset($data['deploymentInfo']['previousRevision']['appSpecContent']['sha256']) ? (string) $data['deploymentInfo']['previousRevision']['appSpecContent']['sha256'] : null,
                ]),
            ]),
            'revision' => empty($data['deploymentInfo']['revision']) ? null : new RevisionLocation([
                'revisionType' => isset($data['deploymentInfo']['revision']['revisionType']) ? (string) $data['deploymentInfo']['revision']['revisionType'] : null,
                's3Location' => empty($data['deploymentInfo']['revision']['s3Location']) ? null : new S3Location([
                    'bucket' => isset($data['deploymentInfo']['revision']['s3Location']['bucket']) ? (string) $data['deploymentInfo']['revision']['s3Location']['bucket'] : null,
                    'key' => isset($data['deploymentInfo']['revision']['s3Location']['key']) ? (string) $data['deploymentInfo']['revision']['s3Location']['key'] : null,
                    'bundleType' => isset($data['deploymentInfo']['revision']['s3Location']['bundleType']) ? (string) $data['deploymentInfo']['revision']['s3Location']['bundleType'] : null,
                    'version' => isset($data['deploymentInfo']['revision']['s3Location']['version']) ? (string) $data['deploymentInfo']['revision']['s3Location']['version'] : null,
                    'eTag' => isset($data['deploymentInfo']['revision']['s3Location']['eTag']) ? (string) $data['deploymentInfo']['revision']['s3Location']['eTag'] : null,
                ]),
                'gitHubLocation' => empty($data['deploymentInfo']['revision']['gitHubLocation']) ? null : new GitHubLocation([
                    'repository' => isset($data['deploymentInfo']['revision']['gitHubLocation']['repository']) ? (string) $data['deploymentInfo']['revision']['gitHubLocation']['repository'] : null,
                    'commitId' => isset($data['deploymentInfo']['revision']['gitHubLocation']['commitId']) ? (string) $data['deploymentInfo']['revision']['gitHubLocation']['commitId'] : null,
                ]),
                'string' => empty($data['deploymentInfo']['revision']['string']) ? null : new RawString([
                    'content' => isset($data['deploymentInfo']['revision']['string']['content']) ? (string) $data['deploymentInfo']['revision']['string']['content'] : null,
                    'sha256' => isset($data['deploymentInfo']['revision']['string']['sha256']) ? (string) $data['deploymentInfo']['revision']['string']['sha256'] : null,
                ]),
                'appSpecContent' => empty($data['deploymentInfo']['revision']['appSpecContent']) ? null : new AppSpecContent([
                    'content' => isset($data['deploymentInfo']['revision']['appSpecContent']['content']) ? (string) $data['deploymentInfo']['revision']['appSpecContent']['content'] : null,
                    'sha256' => isset($data['deploymentInfo']['revision']['appSpecContent']['sha256']) ? (string) $data['deploymentInfo']['revision']['appSpecContent']['sha256'] : null,
                ]),
            ]),
            'status' => isset($data['deploymentInfo']['status']) ? (string) $data['deploymentInfo']['status'] : null,
            'errorInformation' => empty($data['deploymentInfo']['errorInformation']) ? null : new ErrorInformation([
                'code' => isset($data['deploymentInfo']['errorInformation']['code']) ? (string) $data['deploymentInfo']['errorInformation']['code'] : null,
                'message' => isset($data['deploymentInfo']['errorInformation']['message']) ? (string) $data['deploymentInfo']['errorInformation']['message'] : null,
            ]),
            'createTime' => (isset($data['deploymentInfo']['createTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['deploymentInfo']['createTime'])))) ? $d : null,
            'startTime' => (isset($data['deploymentInfo']['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['deploymentInfo']['startTime'])))) ? $d : null,
            'completeTime' => (isset($data['deploymentInfo']['completeTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['deploymentInfo']['completeTime'])))) ? $d : null,
            'deploymentOverview' => empty($data['deploymentInfo']['deploymentOverview']) ? null : new DeploymentOverview([
                'Pending' => isset($data['deploymentInfo']['deploymentOverview']['Pending']) ? (string) $data['deploymentInfo']['deploymentOverview']['Pending'] : null,
                'InProgress' => isset($data['deploymentInfo']['deploymentOverview']['InProgress']) ? (string) $data['deploymentInfo']['deploymentOverview']['InProgress'] : null,
                'Succeeded' => isset($data['deploymentInfo']['deploymentOverview']['Succeeded']) ? (string) $data['deploymentInfo']['deploymentOverview']['Succeeded'] : null,
                'Failed' => isset($data['deploymentInfo']['deploymentOverview']['Failed']) ? (string) $data['deploymentInfo']['deploymentOverview']['Failed'] : null,
                'Skipped' => isset($data['deploymentInfo']['deploymentOverview']['Skipped']) ? (string) $data['deploymentInfo']['deploymentOverview']['Skipped'] : null,
                'Ready' => isset($data['deploymentInfo']['deploymentOverview']['Ready']) ? (string) $data['deploymentInfo']['deploymentOverview']['Ready'] : null,
            ]),
            'description' => isset($data['deploymentInfo']['description']) ? (string) $data['deploymentInfo']['description'] : null,
            'creator' => isset($data['deploymentInfo']['creator']) ? (string) $data['deploymentInfo']['creator'] : null,
            'ignoreApplicationStopFailures' => isset($data['deploymentInfo']['ignoreApplicationStopFailures']) ? filter_var($data['deploymentInfo']['ignoreApplicationStopFailures'], \FILTER_VALIDATE_BOOLEAN) : null,
            'autoRollbackConfiguration' => empty($data['deploymentInfo']['autoRollbackConfiguration']) ? null : new AutoRollbackConfiguration([
                'enabled' => isset($data['deploymentInfo']['autoRollbackConfiguration']['enabled']) ? filter_var($data['deploymentInfo']['autoRollbackConfiguration']['enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                'events' => !isset($data['deploymentInfo']['autoRollbackConfiguration']['events']) ? null : $this->populateResultAutoRollbackEventsList($data['deploymentInfo']['autoRollbackConfiguration']['events']),
            ]),
            'updateOutdatedInstancesOnly' => isset($data['deploymentInfo']['updateOutdatedInstancesOnly']) ? filter_var($data['deploymentInfo']['updateOutdatedInstancesOnly'], \FILTER_VALIDATE_BOOLEAN) : null,
            'rollbackInfo' => empty($data['deploymentInfo']['rollbackInfo']) ? null : new RollbackInfo([
                'rollbackDeploymentId' => isset($data['deploymentInfo']['rollbackInfo']['rollbackDeploymentId']) ? (string) $data['deploymentInfo']['rollbackInfo']['rollbackDeploymentId'] : null,
                'rollbackTriggeringDeploymentId' => isset($data['deploymentInfo']['rollbackInfo']['rollbackTriggeringDeploymentId']) ? (string) $data['deploymentInfo']['rollbackInfo']['rollbackTriggeringDeploymentId'] : null,
                'rollbackMessage' => isset($data['deploymentInfo']['rollbackInfo']['rollbackMessage']) ? (string) $data['deploymentInfo']['rollbackInfo']['rollbackMessage'] : null,
            ]),
            'deploymentStyle' => empty($data['deploymentInfo']['deploymentStyle']) ? null : new DeploymentStyle([
                'deploymentType' => isset($data['deploymentInfo']['deploymentStyle']['deploymentType']) ? (string) $data['deploymentInfo']['deploymentStyle']['deploymentType'] : null,
                'deploymentOption' => isset($data['deploymentInfo']['deploymentStyle']['deploymentOption']) ? (string) $data['deploymentInfo']['deploymentStyle']['deploymentOption'] : null,
            ]),
            'targetInstances' => empty($data['deploymentInfo']['targetInstances']) ? null : new TargetInstances([
                'tagFilters' => !isset($data['deploymentInfo']['targetInstances']['tagFilters']) ? null : $this->populateResultEC2TagFilterList($data['deploymentInfo']['targetInstances']['tagFilters']),
                'autoScalingGroups' => !isset($data['deploymentInfo']['targetInstances']['autoScalingGroups']) ? null : $this->populateResultAutoScalingGroupNameList($data['deploymentInfo']['targetInstances']['autoScalingGroups']),
                'ec2TagSet' => empty($data['deploymentInfo']['targetInstances']['ec2TagSet']) ? null : new EC2TagSet([
                    'ec2TagSetList' => !isset($data['deploymentInfo']['targetInstances']['ec2TagSet']['ec2TagSetList']) ? null : $this->populateResultEC2TagSetList($data['deploymentInfo']['targetInstances']['ec2TagSet']['ec2TagSetList']),
                ]),
            ]),
            'instanceTerminationWaitTimeStarted' => isset($data['deploymentInfo']['instanceTerminationWaitTimeStarted']) ? filter_var($data['deploymentInfo']['instanceTerminationWaitTimeStarted'], \FILTER_VALIDATE_BOOLEAN) : null,
            'blueGreenDeploymentConfiguration' => empty($data['deploymentInfo']['blueGreenDeploymentConfiguration']) ? null : new BlueGreenDeploymentConfiguration([
                'terminateBlueInstancesOnDeploymentSuccess' => empty($data['deploymentInfo']['blueGreenDeploymentConfiguration']['terminateBlueInstancesOnDeploymentSuccess']) ? null : new BlueInstanceTerminationOption([
                    'action' => isset($data['deploymentInfo']['blueGreenDeploymentConfiguration']['terminateBlueInstancesOnDeploymentSuccess']['action']) ? (string) $data['deploymentInfo']['blueGreenDeploymentConfiguration']['terminateBlueInstancesOnDeploymentSuccess']['action'] : null,
                    'terminationWaitTimeInMinutes' => isset($data['deploymentInfo']['blueGreenDeploymentConfiguration']['terminateBlueInstancesOnDeploymentSuccess']['terminationWaitTimeInMinutes']) ? (int) $data['deploymentInfo']['blueGreenDeploymentConfiguration']['terminateBlueInstancesOnDeploymentSuccess']['terminationWaitTimeInMinutes'] : null,
                ]),
                'deploymentReadyOption' => empty($data['deploymentInfo']['blueGreenDeploymentConfiguration']['deploymentReadyOption']) ? null : new DeploymentReadyOption([
                    'actionOnTimeout' => isset($data['deploymentInfo']['blueGreenDeploymentConfiguration']['deploymentReadyOption']['actionOnTimeout']) ? (string) $data['deploymentInfo']['blueGreenDeploymentConfiguration']['deploymentReadyOption']['actionOnTimeout'] : null,
                    'waitTimeInMinutes' => isset($data['deploymentInfo']['blueGreenDeploymentConfiguration']['deploymentReadyOption']['waitTimeInMinutes']) ? (int) $data['deploymentInfo']['blueGreenDeploymentConfiguration']['deploymentReadyOption']['waitTimeInMinutes'] : null,
                ]),
                'greenFleetProvisioningOption' => empty($data['deploymentInfo']['blueGreenDeploymentConfiguration']['greenFleetProvisioningOption']) ? null : new GreenFleetProvisioningOption([
                    'action' => isset($data['deploymentInfo']['blueGreenDeploymentConfiguration']['greenFleetProvisioningOption']['action']) ? (string) $data['deploymentInfo']['blueGreenDeploymentConfiguration']['greenFleetProvisioningOption']['action'] : null,
                ]),
            ]),
            'loadBalancerInfo' => empty($data['deploymentInfo']['loadBalancerInfo']) ? null : new LoadBalancerInfo([
                'elbInfoList' => !isset($data['deploymentInfo']['loadBalancerInfo']['elbInfoList']) ? null : $this->populateResultELBInfoList($data['deploymentInfo']['loadBalancerInfo']['elbInfoList']),
                'targetGroupInfoList' => !isset($data['deploymentInfo']['loadBalancerInfo']['targetGroupInfoList']) ? null : $this->populateResultTargetGroupInfoList($data['deploymentInfo']['loadBalancerInfo']['targetGroupInfoList']),
                'targetGroupPairInfoList' => !isset($data['deploymentInfo']['loadBalancerInfo']['targetGroupPairInfoList']) ? null : $this->populateResultTargetGroupPairInfoList($data['deploymentInfo']['loadBalancerInfo']['targetGroupPairInfoList']),
            ]),
            'additionalDeploymentStatusInfo' => isset($data['deploymentInfo']['additionalDeploymentStatusInfo']) ? (string) $data['deploymentInfo']['additionalDeploymentStatusInfo'] : null,
            'fileExistsBehavior' => isset($data['deploymentInfo']['fileExistsBehavior']) ? (string) $data['deploymentInfo']['fileExistsBehavior'] : null,
            'deploymentStatusMessages' => !isset($data['deploymentInfo']['deploymentStatusMessages']) ? null : $this->populateResultDeploymentStatusMessageList($data['deploymentInfo']['deploymentStatusMessages']),
            'computePlatform' => isset($data['deploymentInfo']['computePlatform']) ? (string) $data['deploymentInfo']['computePlatform'] : null,
            'externalId' => isset($data['deploymentInfo']['externalId']) ? (string) $data['deploymentInfo']['externalId'] : null,
            'relatedDeployments' => empty($data['deploymentInfo']['relatedDeployments']) ? null : new RelatedDeployments([
                'autoUpdateOutdatedInstancesRootDeploymentId' => isset($data['deploymentInfo']['relatedDeployments']['autoUpdateOutdatedInstancesRootDeploymentId']) ? (string) $data['deploymentInfo']['relatedDeployments']['autoUpdateOutdatedInstancesRootDeploymentId'] : null,
                'autoUpdateOutdatedInstancesDeploymentIds' => !isset($data['deploymentInfo']['relatedDeployments']['autoUpdateOutdatedInstancesDeploymentIds']) ? null : $this->populateResultDeploymentsList($data['deploymentInfo']['relatedDeployments']['autoUpdateOutdatedInstancesDeploymentIds']),
            ]),
        ]);
    }

    /**
     * @return list<AutoRollbackEvent::*>
     */
    private function populateResultAutoRollbackEventsList(array $json): array
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
    private function populateResultAutoScalingGroupNameList(array $json): array
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
    private function populateResultDeploymentStatusMessageList(array $json): array
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
    private function populateResultDeploymentsList(array $json): array
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
     * @return EC2TagFilter[]
     */
    private function populateResultEC2TagFilterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new EC2TagFilter([
                'Key' => isset($item['Key']) ? (string) $item['Key'] : null,
                'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return EC2TagFilter[][]
     */
    private function populateResultEC2TagSetList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEC2TagFilterList($item);
        }

        return $items;
    }

    /**
     * @return ELBInfo[]
     */
    private function populateResultELBInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ELBInfo([
                'name' => isset($item['name']) ? (string) $item['name'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultListenerArnList(array $json): array
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
     * @return TargetGroupInfo[]
     */
    private function populateResultTargetGroupInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new TargetGroupInfo([
                'name' => isset($item['name']) ? (string) $item['name'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return TargetGroupPairInfo[]
     */
    private function populateResultTargetGroupPairInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new TargetGroupPairInfo([
                'targetGroups' => !isset($item['targetGroups']) ? null : $this->populateResultTargetGroupInfoList($item['targetGroups']),
                'prodTrafficRoute' => empty($item['prodTrafficRoute']) ? null : new TrafficRoute([
                    'listenerArns' => !isset($item['prodTrafficRoute']['listenerArns']) ? null : $this->populateResultListenerArnList($item['prodTrafficRoute']['listenerArns']),
                ]),
                'testTrafficRoute' => empty($item['testTrafficRoute']) ? null : new TrafficRoute([
                    'listenerArns' => !isset($item['testTrafficRoute']['listenerArns']) ? null : $this->populateResultListenerArnList($item['testTrafficRoute']['listenerArns']),
                ]),
            ]);
        }

        return $items;
    }
}
