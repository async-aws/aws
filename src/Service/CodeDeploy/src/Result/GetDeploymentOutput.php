<?php

namespace AsyncAws\CodeDeploy\Result;

use AsyncAws\CodeDeploy\Enum\AutoRollbackEvent;
use AsyncAws\CodeDeploy\ValueObject\Alarm;
use AsyncAws\CodeDeploy\ValueObject\AlarmConfiguration;
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
     *
     * @var DeploymentInfo|null
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

        $this->deploymentInfo = empty($data['deploymentInfo']) ? null : $this->populateResultDeploymentInfo($data['deploymentInfo']);
    }

    private function populateResultAlarm(array $json): Alarm
    {
        return new Alarm([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
        ]);
    }

    private function populateResultAlarmConfiguration(array $json): AlarmConfiguration
    {
        return new AlarmConfiguration([
            'enabled' => isset($json['enabled']) ? filter_var($json['enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'ignorePollAlarmFailure' => isset($json['ignorePollAlarmFailure']) ? filter_var($json['ignorePollAlarmFailure'], \FILTER_VALIDATE_BOOLEAN) : null,
            'alarms' => !isset($json['alarms']) ? null : $this->populateResultAlarmList($json['alarms']),
        ]);
    }

    /**
     * @return Alarm[]
     */
    private function populateResultAlarmList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAlarm($item);
        }

        return $items;
    }

    private function populateResultAppSpecContent(array $json): AppSpecContent
    {
        return new AppSpecContent([
            'content' => isset($json['content']) ? (string) $json['content'] : null,
            'sha256' => isset($json['sha256']) ? (string) $json['sha256'] : null,
        ]);
    }

    private function populateResultAutoRollbackConfiguration(array $json): AutoRollbackConfiguration
    {
        return new AutoRollbackConfiguration([
            'enabled' => isset($json['enabled']) ? filter_var($json['enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'events' => !isset($json['events']) ? null : $this->populateResultAutoRollbackEventsList($json['events']),
        ]);
    }

    /**
     * @return list<AutoRollbackEvent::*|string>
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

    private function populateResultBlueGreenDeploymentConfiguration(array $json): BlueGreenDeploymentConfiguration
    {
        return new BlueGreenDeploymentConfiguration([
            'terminateBlueInstancesOnDeploymentSuccess' => empty($json['terminateBlueInstancesOnDeploymentSuccess']) ? null : $this->populateResultBlueInstanceTerminationOption($json['terminateBlueInstancesOnDeploymentSuccess']),
            'deploymentReadyOption' => empty($json['deploymentReadyOption']) ? null : $this->populateResultDeploymentReadyOption($json['deploymentReadyOption']),
            'greenFleetProvisioningOption' => empty($json['greenFleetProvisioningOption']) ? null : $this->populateResultGreenFleetProvisioningOption($json['greenFleetProvisioningOption']),
        ]);
    }

    private function populateResultBlueInstanceTerminationOption(array $json): BlueInstanceTerminationOption
    {
        return new BlueInstanceTerminationOption([
            'action' => isset($json['action']) ? (string) $json['action'] : null,
            'terminationWaitTimeInMinutes' => isset($json['terminationWaitTimeInMinutes']) ? (int) $json['terminationWaitTimeInMinutes'] : null,
        ]);
    }

    private function populateResultDeploymentInfo(array $json): DeploymentInfo
    {
        return new DeploymentInfo([
            'applicationName' => isset($json['applicationName']) ? (string) $json['applicationName'] : null,
            'deploymentGroupName' => isset($json['deploymentGroupName']) ? (string) $json['deploymentGroupName'] : null,
            'deploymentConfigName' => isset($json['deploymentConfigName']) ? (string) $json['deploymentConfigName'] : null,
            'deploymentId' => isset($json['deploymentId']) ? (string) $json['deploymentId'] : null,
            'previousRevision' => empty($json['previousRevision']) ? null : $this->populateResultRevisionLocation($json['previousRevision']),
            'revision' => empty($json['revision']) ? null : $this->populateResultRevisionLocation($json['revision']),
            'status' => isset($json['status']) ? (string) $json['status'] : null,
            'errorInformation' => empty($json['errorInformation']) ? null : $this->populateResultErrorInformation($json['errorInformation']),
            'createTime' => (isset($json['createTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['createTime'])))) ? $d : null,
            'startTime' => (isset($json['startTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['startTime'])))) ? $d : null,
            'completeTime' => (isset($json['completeTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['completeTime'])))) ? $d : null,
            'deploymentOverview' => empty($json['deploymentOverview']) ? null : $this->populateResultDeploymentOverview($json['deploymentOverview']),
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'creator' => isset($json['creator']) ? (string) $json['creator'] : null,
            'ignoreApplicationStopFailures' => isset($json['ignoreApplicationStopFailures']) ? filter_var($json['ignoreApplicationStopFailures'], \FILTER_VALIDATE_BOOLEAN) : null,
            'autoRollbackConfiguration' => empty($json['autoRollbackConfiguration']) ? null : $this->populateResultAutoRollbackConfiguration($json['autoRollbackConfiguration']),
            'updateOutdatedInstancesOnly' => isset($json['updateOutdatedInstancesOnly']) ? filter_var($json['updateOutdatedInstancesOnly'], \FILTER_VALIDATE_BOOLEAN) : null,
            'rollbackInfo' => empty($json['rollbackInfo']) ? null : $this->populateResultRollbackInfo($json['rollbackInfo']),
            'deploymentStyle' => empty($json['deploymentStyle']) ? null : $this->populateResultDeploymentStyle($json['deploymentStyle']),
            'targetInstances' => empty($json['targetInstances']) ? null : $this->populateResultTargetInstances($json['targetInstances']),
            'instanceTerminationWaitTimeStarted' => isset($json['instanceTerminationWaitTimeStarted']) ? filter_var($json['instanceTerminationWaitTimeStarted'], \FILTER_VALIDATE_BOOLEAN) : null,
            'blueGreenDeploymentConfiguration' => empty($json['blueGreenDeploymentConfiguration']) ? null : $this->populateResultBlueGreenDeploymentConfiguration($json['blueGreenDeploymentConfiguration']),
            'loadBalancerInfo' => empty($json['loadBalancerInfo']) ? null : $this->populateResultLoadBalancerInfo($json['loadBalancerInfo']),
            'additionalDeploymentStatusInfo' => isset($json['additionalDeploymentStatusInfo']) ? (string) $json['additionalDeploymentStatusInfo'] : null,
            'fileExistsBehavior' => isset($json['fileExistsBehavior']) ? (string) $json['fileExistsBehavior'] : null,
            'deploymentStatusMessages' => !isset($json['deploymentStatusMessages']) ? null : $this->populateResultDeploymentStatusMessageList($json['deploymentStatusMessages']),
            'computePlatform' => isset($json['computePlatform']) ? (string) $json['computePlatform'] : null,
            'externalId' => isset($json['externalId']) ? (string) $json['externalId'] : null,
            'relatedDeployments' => empty($json['relatedDeployments']) ? null : $this->populateResultRelatedDeployments($json['relatedDeployments']),
            'overrideAlarmConfiguration' => empty($json['overrideAlarmConfiguration']) ? null : $this->populateResultAlarmConfiguration($json['overrideAlarmConfiguration']),
        ]);
    }

    private function populateResultDeploymentOverview(array $json): DeploymentOverview
    {
        return new DeploymentOverview([
            'Pending' => isset($json['Pending']) ? (int) $json['Pending'] : null,
            'InProgress' => isset($json['InProgress']) ? (int) $json['InProgress'] : null,
            'Succeeded' => isset($json['Succeeded']) ? (int) $json['Succeeded'] : null,
            'Failed' => isset($json['Failed']) ? (int) $json['Failed'] : null,
            'Skipped' => isset($json['Skipped']) ? (int) $json['Skipped'] : null,
            'Ready' => isset($json['Ready']) ? (int) $json['Ready'] : null,
        ]);
    }

    private function populateResultDeploymentReadyOption(array $json): DeploymentReadyOption
    {
        return new DeploymentReadyOption([
            'actionOnTimeout' => isset($json['actionOnTimeout']) ? (string) $json['actionOnTimeout'] : null,
            'waitTimeInMinutes' => isset($json['waitTimeInMinutes']) ? (int) $json['waitTimeInMinutes'] : null,
        ]);
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

    private function populateResultDeploymentStyle(array $json): DeploymentStyle
    {
        return new DeploymentStyle([
            'deploymentType' => isset($json['deploymentType']) ? (string) $json['deploymentType'] : null,
            'deploymentOption' => isset($json['deploymentOption']) ? (string) $json['deploymentOption'] : null,
        ]);
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

    private function populateResultEC2TagFilter(array $json): EC2TagFilter
    {
        return new EC2TagFilter([
            'Key' => isset($json['Key']) ? (string) $json['Key'] : null,
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
        ]);
    }

    /**
     * @return EC2TagFilter[]
     */
    private function populateResultEC2TagFilterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEC2TagFilter($item);
        }

        return $items;
    }

    private function populateResultEC2TagSet(array $json): EC2TagSet
    {
        return new EC2TagSet([
            'ec2TagSetList' => !isset($json['ec2TagSetList']) ? null : $this->populateResultEC2TagSetList($json['ec2TagSetList']),
        ]);
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

    private function populateResultELBInfo(array $json): ELBInfo
    {
        return new ELBInfo([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
        ]);
    }

    /**
     * @return ELBInfo[]
     */
    private function populateResultELBInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultELBInfo($item);
        }

        return $items;
    }

    private function populateResultErrorInformation(array $json): ErrorInformation
    {
        return new ErrorInformation([
            'code' => isset($json['code']) ? (string) $json['code'] : null,
            'message' => isset($json['message']) ? (string) $json['message'] : null,
        ]);
    }

    private function populateResultGitHubLocation(array $json): GitHubLocation
    {
        return new GitHubLocation([
            'repository' => isset($json['repository']) ? (string) $json['repository'] : null,
            'commitId' => isset($json['commitId']) ? (string) $json['commitId'] : null,
        ]);
    }

    private function populateResultGreenFleetProvisioningOption(array $json): GreenFleetProvisioningOption
    {
        return new GreenFleetProvisioningOption([
            'action' => isset($json['action']) ? (string) $json['action'] : null,
        ]);
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

    private function populateResultLoadBalancerInfo(array $json): LoadBalancerInfo
    {
        return new LoadBalancerInfo([
            'elbInfoList' => !isset($json['elbInfoList']) ? null : $this->populateResultELBInfoList($json['elbInfoList']),
            'targetGroupInfoList' => !isset($json['targetGroupInfoList']) ? null : $this->populateResultTargetGroupInfoList($json['targetGroupInfoList']),
            'targetGroupPairInfoList' => !isset($json['targetGroupPairInfoList']) ? null : $this->populateResultTargetGroupPairInfoList($json['targetGroupPairInfoList']),
        ]);
    }

    private function populateResultRawString(array $json): RawString
    {
        return new RawString([
            'content' => isset($json['content']) ? (string) $json['content'] : null,
            'sha256' => isset($json['sha256']) ? (string) $json['sha256'] : null,
        ]);
    }

    private function populateResultRelatedDeployments(array $json): RelatedDeployments
    {
        return new RelatedDeployments([
            'autoUpdateOutdatedInstancesRootDeploymentId' => isset($json['autoUpdateOutdatedInstancesRootDeploymentId']) ? (string) $json['autoUpdateOutdatedInstancesRootDeploymentId'] : null,
            'autoUpdateOutdatedInstancesDeploymentIds' => !isset($json['autoUpdateOutdatedInstancesDeploymentIds']) ? null : $this->populateResultDeploymentsList($json['autoUpdateOutdatedInstancesDeploymentIds']),
        ]);
    }

    private function populateResultRevisionLocation(array $json): RevisionLocation
    {
        return new RevisionLocation([
            'revisionType' => isset($json['revisionType']) ? (string) $json['revisionType'] : null,
            's3Location' => empty($json['s3Location']) ? null : $this->populateResultS3Location($json['s3Location']),
            'gitHubLocation' => empty($json['gitHubLocation']) ? null : $this->populateResultGitHubLocation($json['gitHubLocation']),
            'string' => empty($json['string']) ? null : $this->populateResultRawString($json['string']),
            'appSpecContent' => empty($json['appSpecContent']) ? null : $this->populateResultAppSpecContent($json['appSpecContent']),
        ]);
    }

    private function populateResultRollbackInfo(array $json): RollbackInfo
    {
        return new RollbackInfo([
            'rollbackDeploymentId' => isset($json['rollbackDeploymentId']) ? (string) $json['rollbackDeploymentId'] : null,
            'rollbackTriggeringDeploymentId' => isset($json['rollbackTriggeringDeploymentId']) ? (string) $json['rollbackTriggeringDeploymentId'] : null,
            'rollbackMessage' => isset($json['rollbackMessage']) ? (string) $json['rollbackMessage'] : null,
        ]);
    }

    private function populateResultS3Location(array $json): S3Location
    {
        return new S3Location([
            'bucket' => isset($json['bucket']) ? (string) $json['bucket'] : null,
            'key' => isset($json['key']) ? (string) $json['key'] : null,
            'bundleType' => isset($json['bundleType']) ? (string) $json['bundleType'] : null,
            'version' => isset($json['version']) ? (string) $json['version'] : null,
            'eTag' => isset($json['eTag']) ? (string) $json['eTag'] : null,
        ]);
    }

    private function populateResultTargetGroupInfo(array $json): TargetGroupInfo
    {
        return new TargetGroupInfo([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
        ]);
    }

    /**
     * @return TargetGroupInfo[]
     */
    private function populateResultTargetGroupInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTargetGroupInfo($item);
        }

        return $items;
    }

    private function populateResultTargetGroupPairInfo(array $json): TargetGroupPairInfo
    {
        return new TargetGroupPairInfo([
            'targetGroups' => !isset($json['targetGroups']) ? null : $this->populateResultTargetGroupInfoList($json['targetGroups']),
            'prodTrafficRoute' => empty($json['prodTrafficRoute']) ? null : $this->populateResultTrafficRoute($json['prodTrafficRoute']),
            'testTrafficRoute' => empty($json['testTrafficRoute']) ? null : $this->populateResultTrafficRoute($json['testTrafficRoute']),
        ]);
    }

    /**
     * @return TargetGroupPairInfo[]
     */
    private function populateResultTargetGroupPairInfoList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultTargetGroupPairInfo($item);
        }

        return $items;
    }

    private function populateResultTargetInstances(array $json): TargetInstances
    {
        return new TargetInstances([
            'tagFilters' => !isset($json['tagFilters']) ? null : $this->populateResultEC2TagFilterList($json['tagFilters']),
            'autoScalingGroups' => !isset($json['autoScalingGroups']) ? null : $this->populateResultAutoScalingGroupNameList($json['autoScalingGroups']),
            'ec2TagSet' => empty($json['ec2TagSet']) ? null : $this->populateResultEC2TagSet($json['ec2TagSet']),
        ]);
    }

    private function populateResultTrafficRoute(array $json): TrafficRoute
    {
        return new TrafficRoute([
            'listenerArns' => !isset($json['listenerArns']) ? null : $this->populateResultListenerArnList($json['listenerArns']),
        ]);
    }
}
