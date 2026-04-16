<?php

namespace AsyncAws\ImageBuilder\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\ImageBuilder\Enum\BuildType;
use AsyncAws\ImageBuilder\Enum\ContainerRepositoryService;
use AsyncAws\ImageBuilder\Enum\ContainerType;
use AsyncAws\ImageBuilder\Enum\DiskImageFormat;
use AsyncAws\ImageBuilder\Enum\EbsVolumeType;
use AsyncAws\ImageBuilder\Enum\ImageScanStatus;
use AsyncAws\ImageBuilder\Enum\ImageSource;
use AsyncAws\ImageBuilder\Enum\ImageStatus;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\OnWorkflowFailure;
use AsyncAws\ImageBuilder\Enum\Platform;
use AsyncAws\ImageBuilder\Enum\SsmParameterDataType;
use AsyncAws\ImageBuilder\Enum\TenancyType;
use AsyncAws\ImageBuilder\ValueObject\AdditionalInstanceConfiguration;
use AsyncAws\ImageBuilder\ValueObject\Ami;
use AsyncAws\ImageBuilder\ValueObject\AmiDistributionConfiguration;
use AsyncAws\ImageBuilder\ValueObject\ComponentConfiguration;
use AsyncAws\ImageBuilder\ValueObject\ComponentParameter;
use AsyncAws\ImageBuilder\ValueObject\Container;
use AsyncAws\ImageBuilder\ValueObject\ContainerDistributionConfiguration;
use AsyncAws\ImageBuilder\ValueObject\ContainerRecipe;
use AsyncAws\ImageBuilder\ValueObject\Distribution;
use AsyncAws\ImageBuilder\ValueObject\DistributionConfiguration;
use AsyncAws\ImageBuilder\ValueObject\EbsInstanceBlockDeviceSpecification;
use AsyncAws\ImageBuilder\ValueObject\EcrConfiguration;
use AsyncAws\ImageBuilder\ValueObject\FastLaunchConfiguration;
use AsyncAws\ImageBuilder\ValueObject\FastLaunchLaunchTemplateSpecification;
use AsyncAws\ImageBuilder\ValueObject\FastLaunchSnapshotConfiguration;
use AsyncAws\ImageBuilder\ValueObject\Image;
use AsyncAws\ImageBuilder\ValueObject\ImageLoggingConfiguration;
use AsyncAws\ImageBuilder\ValueObject\ImageRecipe;
use AsyncAws\ImageBuilder\ValueObject\ImageScanningConfiguration;
use AsyncAws\ImageBuilder\ValueObject\ImageScanState;
use AsyncAws\ImageBuilder\ValueObject\ImageState;
use AsyncAws\ImageBuilder\ValueObject\ImageTestsConfiguration;
use AsyncAws\ImageBuilder\ValueObject\InfrastructureConfiguration;
use AsyncAws\ImageBuilder\ValueObject\InstanceBlockDeviceMapping;
use AsyncAws\ImageBuilder\ValueObject\InstanceConfiguration;
use AsyncAws\ImageBuilder\ValueObject\InstanceMetadataOptions;
use AsyncAws\ImageBuilder\ValueObject\LatestVersionReferences;
use AsyncAws\ImageBuilder\ValueObject\LaunchPermissionConfiguration;
use AsyncAws\ImageBuilder\ValueObject\LaunchTemplateConfiguration;
use AsyncAws\ImageBuilder\ValueObject\Logging;
use AsyncAws\ImageBuilder\ValueObject\OutputResources;
use AsyncAws\ImageBuilder\ValueObject\Placement;
use AsyncAws\ImageBuilder\ValueObject\S3ExportConfiguration;
use AsyncAws\ImageBuilder\ValueObject\S3Logs;
use AsyncAws\ImageBuilder\ValueObject\SsmParameterConfiguration;
use AsyncAws\ImageBuilder\ValueObject\SystemsManagerAgent;
use AsyncAws\ImageBuilder\ValueObject\TargetContainerRepository;
use AsyncAws\ImageBuilder\ValueObject\WorkflowConfiguration;
use AsyncAws\ImageBuilder\ValueObject\WorkflowParameter;

class GetImageResponse extends Result
{
    /**
     * The request ID that uniquely identifies this request.
     *
     * @var string|null
     */
    private $requestId;

    /**
     * The image object.
     *
     * @var Image|null
     */
    private $image;

    /**
     * The resource ARNs with different wildcard variations of semantic versioning.
     *
     * @var LatestVersionReferences|null
     */
    private $latestVersionReferences;

    public function getImage(): ?Image
    {
        $this->initialize();

        return $this->image;
    }

    public function getLatestVersionReferences(): ?LatestVersionReferences
    {
        $this->initialize();

        return $this->latestVersionReferences;
    }

    public function getRequestId(): ?string
    {
        $this->initialize();

        return $this->requestId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->requestId = isset($data['requestId']) ? (string) $data['requestId'] : null;
        $this->image = empty($data['image']) ? null : $this->populateResultImage($data['image']);
        $this->latestVersionReferences = empty($data['latestVersionReferences']) ? null : $this->populateResultLatestVersionReferences($data['latestVersionReferences']);
    }

    /**
     * @return string[]
     */
    private function populateResultAccountList(array $json): array
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

    private function populateResultAdditionalInstanceConfiguration(array $json): AdditionalInstanceConfiguration
    {
        return new AdditionalInstanceConfiguration([
            'systemsManagerAgent' => empty($json['systemsManagerAgent']) ? null : $this->populateResultSystemsManagerAgent($json['systemsManagerAgent']),
            'userDataOverride' => isset($json['userDataOverride']) ? (string) $json['userDataOverride'] : null,
        ]);
    }

    private function populateResultAmi(array $json): Ami
    {
        return new Ami([
            'region' => isset($json['region']) ? (string) $json['region'] : null,
            'image' => isset($json['image']) ? (string) $json['image'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'state' => empty($json['state']) ? null : $this->populateResultImageState($json['state']),
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
        ]);
    }

    private function populateResultAmiDistributionConfiguration(array $json): AmiDistributionConfiguration
    {
        return new AmiDistributionConfiguration([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'targetAccountIds' => !isset($json['targetAccountIds']) ? null : $this->populateResultAccountList($json['targetAccountIds']),
            'amiTags' => !isset($json['amiTags']) ? null : $this->populateResultTagMap($json['amiTags']),
            'kmsKeyId' => isset($json['kmsKeyId']) ? (string) $json['kmsKeyId'] : null,
            'launchPermission' => empty($json['launchPermission']) ? null : $this->populateResultLaunchPermissionConfiguration($json['launchPermission']),
        ]);
    }

    /**
     * @return Ami[]
     */
    private function populateResultAmiList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAmi($item);
        }

        return $items;
    }

    private function populateResultComponentConfiguration(array $json): ComponentConfiguration
    {
        return new ComponentConfiguration([
            'componentArn' => (string) $json['componentArn'],
            'parameters' => !isset($json['parameters']) ? null : $this->populateResultComponentParameterList($json['parameters']),
        ]);
    }

    /**
     * @return ComponentConfiguration[]
     */
    private function populateResultComponentConfigurationList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultComponentConfiguration($item);
        }

        return $items;
    }

    private function populateResultComponentParameter(array $json): ComponentParameter
    {
        return new ComponentParameter([
            'name' => (string) $json['name'],
            'value' => $this->populateResultComponentParameterValueList($json['value']),
        ]);
    }

    /**
     * @return ComponentParameter[]
     */
    private function populateResultComponentParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultComponentParameter($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultComponentParameterValueList(array $json): array
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

    private function populateResultContainer(array $json): Container
    {
        return new Container([
            'region' => isset($json['region']) ? (string) $json['region'] : null,
            'imageUris' => !isset($json['imageUris']) ? null : $this->populateResultStringList($json['imageUris']),
        ]);
    }

    private function populateResultContainerDistributionConfiguration(array $json): ContainerDistributionConfiguration
    {
        return new ContainerDistributionConfiguration([
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'containerTags' => !isset($json['containerTags']) ? null : $this->populateResultStringList($json['containerTags']),
            'targetRepository' => $this->populateResultTargetContainerRepository($json['targetRepository']),
        ]);
    }

    /**
     * @return Container[]
     */
    private function populateResultContainerList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultContainer($item);
        }

        return $items;
    }

    private function populateResultContainerRecipe(array $json): ContainerRecipe
    {
        return new ContainerRecipe([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'containerType' => isset($json['containerType']) ? (!ContainerType::exists((string) $json['containerType']) ? ContainerType::UNKNOWN_TO_SDK : (string) $json['containerType']) : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'platform' => isset($json['platform']) ? (!Platform::exists((string) $json['platform']) ? Platform::UNKNOWN_TO_SDK : (string) $json['platform']) : null,
            'owner' => isset($json['owner']) ? (string) $json['owner'] : null,
            'version' => isset($json['version']) ? (string) $json['version'] : null,
            'components' => !isset($json['components']) ? null : $this->populateResultComponentConfigurationList($json['components']),
            'instanceConfiguration' => empty($json['instanceConfiguration']) ? null : $this->populateResultInstanceConfiguration($json['instanceConfiguration']),
            'dockerfileTemplateData' => isset($json['dockerfileTemplateData']) ? (string) $json['dockerfileTemplateData'] : null,
            'kmsKeyId' => isset($json['kmsKeyId']) ? (string) $json['kmsKeyId'] : null,
            'encrypted' => isset($json['encrypted']) ? filter_var($json['encrypted'], \FILTER_VALIDATE_BOOLEAN) : null,
            'parentImage' => isset($json['parentImage']) ? (string) $json['parentImage'] : null,
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'tags' => !isset($json['tags']) ? null : $this->populateResultTagMap($json['tags']),
            'workingDirectory' => isset($json['workingDirectory']) ? (string) $json['workingDirectory'] : null,
            'targetRepository' => empty($json['targetRepository']) ? null : $this->populateResultTargetContainerRepository($json['targetRepository']),
        ]);
    }

    private function populateResultDistribution(array $json): Distribution
    {
        return new Distribution([
            'region' => (string) $json['region'],
            'amiDistributionConfiguration' => empty($json['amiDistributionConfiguration']) ? null : $this->populateResultAmiDistributionConfiguration($json['amiDistributionConfiguration']),
            'containerDistributionConfiguration' => empty($json['containerDistributionConfiguration']) ? null : $this->populateResultContainerDistributionConfiguration($json['containerDistributionConfiguration']),
            'licenseConfigurationArns' => !isset($json['licenseConfigurationArns']) ? null : $this->populateResultLicenseConfigurationArnList($json['licenseConfigurationArns']),
            'launchTemplateConfigurations' => !isset($json['launchTemplateConfigurations']) ? null : $this->populateResultLaunchTemplateConfigurationList($json['launchTemplateConfigurations']),
            's3ExportConfiguration' => empty($json['s3ExportConfiguration']) ? null : $this->populateResultS3ExportConfiguration($json['s3ExportConfiguration']),
            'fastLaunchConfigurations' => !isset($json['fastLaunchConfigurations']) ? null : $this->populateResultFastLaunchConfigurationList($json['fastLaunchConfigurations']),
            'ssmParameterConfigurations' => !isset($json['ssmParameterConfigurations']) ? null : $this->populateResultSsmParameterConfigurationList($json['ssmParameterConfigurations']),
        ]);
    }

    private function populateResultDistributionConfiguration(array $json): DistributionConfiguration
    {
        return new DistributionConfiguration([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'distributions' => !isset($json['distributions']) ? null : $this->populateResultDistributionList($json['distributions']),
            'timeoutMinutes' => (int) $json['timeoutMinutes'],
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'dateUpdated' => isset($json['dateUpdated']) ? (string) $json['dateUpdated'] : null,
            'tags' => !isset($json['tags']) ? null : $this->populateResultTagMap($json['tags']),
        ]);
    }

    /**
     * @return Distribution[]
     */
    private function populateResultDistributionList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDistribution($item);
        }

        return $items;
    }

    private function populateResultEbsInstanceBlockDeviceSpecification(array $json): EbsInstanceBlockDeviceSpecification
    {
        return new EbsInstanceBlockDeviceSpecification([
            'encrypted' => isset($json['encrypted']) ? filter_var($json['encrypted'], \FILTER_VALIDATE_BOOLEAN) : null,
            'deleteOnTermination' => isset($json['deleteOnTermination']) ? filter_var($json['deleteOnTermination'], \FILTER_VALIDATE_BOOLEAN) : null,
            'iops' => isset($json['iops']) ? (int) $json['iops'] : null,
            'kmsKeyId' => isset($json['kmsKeyId']) ? (string) $json['kmsKeyId'] : null,
            'snapshotId' => isset($json['snapshotId']) ? (string) $json['snapshotId'] : null,
            'volumeSize' => isset($json['volumeSize']) ? (int) $json['volumeSize'] : null,
            'volumeType' => isset($json['volumeType']) ? (!EbsVolumeType::exists((string) $json['volumeType']) ? EbsVolumeType::UNKNOWN_TO_SDK : (string) $json['volumeType']) : null,
            'throughput' => isset($json['throughput']) ? (int) $json['throughput'] : null,
        ]);
    }

    private function populateResultEcrConfiguration(array $json): EcrConfiguration
    {
        return new EcrConfiguration([
            'repositoryName' => isset($json['repositoryName']) ? (string) $json['repositoryName'] : null,
            'containerTags' => !isset($json['containerTags']) ? null : $this->populateResultStringList($json['containerTags']),
        ]);
    }

    private function populateResultFastLaunchConfiguration(array $json): FastLaunchConfiguration
    {
        return new FastLaunchConfiguration([
            'enabled' => filter_var($json['enabled'], \FILTER_VALIDATE_BOOLEAN),
            'snapshotConfiguration' => empty($json['snapshotConfiguration']) ? null : $this->populateResultFastLaunchSnapshotConfiguration($json['snapshotConfiguration']),
            'maxParallelLaunches' => isset($json['maxParallelLaunches']) ? (int) $json['maxParallelLaunches'] : null,
            'launchTemplate' => empty($json['launchTemplate']) ? null : $this->populateResultFastLaunchLaunchTemplateSpecification($json['launchTemplate']),
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
        ]);
    }

    /**
     * @return FastLaunchConfiguration[]
     */
    private function populateResultFastLaunchConfigurationList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFastLaunchConfiguration($item);
        }

        return $items;
    }

    private function populateResultFastLaunchLaunchTemplateSpecification(array $json): FastLaunchLaunchTemplateSpecification
    {
        return new FastLaunchLaunchTemplateSpecification([
            'launchTemplateId' => isset($json['launchTemplateId']) ? (string) $json['launchTemplateId'] : null,
            'launchTemplateName' => isset($json['launchTemplateName']) ? (string) $json['launchTemplateName'] : null,
            'launchTemplateVersion' => isset($json['launchTemplateVersion']) ? (string) $json['launchTemplateVersion'] : null,
        ]);
    }

    private function populateResultFastLaunchSnapshotConfiguration(array $json): FastLaunchSnapshotConfiguration
    {
        return new FastLaunchSnapshotConfiguration([
            'targetResourceCount' => isset($json['targetResourceCount']) ? (int) $json['targetResourceCount'] : null,
        ]);
    }

    private function populateResultImage(array $json): Image
    {
        return new Image([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'type' => isset($json['type']) ? (!ImageType::exists((string) $json['type']) ? ImageType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'version' => isset($json['version']) ? (string) $json['version'] : null,
            'platform' => isset($json['platform']) ? (!Platform::exists((string) $json['platform']) ? Platform::UNKNOWN_TO_SDK : (string) $json['platform']) : null,
            'enhancedImageMetadataEnabled' => isset($json['enhancedImageMetadataEnabled']) ? filter_var($json['enhancedImageMetadataEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'osVersion' => isset($json['osVersion']) ? (string) $json['osVersion'] : null,
            'state' => empty($json['state']) ? null : $this->populateResultImageState($json['state']),
            'imageRecipe' => empty($json['imageRecipe']) ? null : $this->populateResultImageRecipe($json['imageRecipe']),
            'containerRecipe' => empty($json['containerRecipe']) ? null : $this->populateResultContainerRecipe($json['containerRecipe']),
            'sourcePipelineName' => isset($json['sourcePipelineName']) ? (string) $json['sourcePipelineName'] : null,
            'sourcePipelineArn' => isset($json['sourcePipelineArn']) ? (string) $json['sourcePipelineArn'] : null,
            'infrastructureConfiguration' => empty($json['infrastructureConfiguration']) ? null : $this->populateResultInfrastructureConfiguration($json['infrastructureConfiguration']),
            'distributionConfiguration' => empty($json['distributionConfiguration']) ? null : $this->populateResultDistributionConfiguration($json['distributionConfiguration']),
            'imageTestsConfiguration' => empty($json['imageTestsConfiguration']) ? null : $this->populateResultImageTestsConfiguration($json['imageTestsConfiguration']),
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'outputResources' => empty($json['outputResources']) ? null : $this->populateResultOutputResources($json['outputResources']),
            'tags' => !isset($json['tags']) ? null : $this->populateResultTagMap($json['tags']),
            'buildType' => isset($json['buildType']) ? (!BuildType::exists((string) $json['buildType']) ? BuildType::UNKNOWN_TO_SDK : (string) $json['buildType']) : null,
            'imageSource' => isset($json['imageSource']) ? (!ImageSource::exists((string) $json['imageSource']) ? ImageSource::UNKNOWN_TO_SDK : (string) $json['imageSource']) : null,
            'scanState' => empty($json['scanState']) ? null : $this->populateResultImageScanState($json['scanState']),
            'imageScanningConfiguration' => empty($json['imageScanningConfiguration']) ? null : $this->populateResultImageScanningConfiguration($json['imageScanningConfiguration']),
            'deprecationTime' => isset($json['deprecationTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['deprecationTime']))) ? $d : null,
            'lifecycleExecutionId' => isset($json['lifecycleExecutionId']) ? (string) $json['lifecycleExecutionId'] : null,
            'executionRole' => isset($json['executionRole']) ? (string) $json['executionRole'] : null,
            'workflows' => !isset($json['workflows']) ? null : $this->populateResultWorkflowConfigurationList($json['workflows']),
            'loggingConfiguration' => empty($json['loggingConfiguration']) ? null : $this->populateResultImageLoggingConfiguration($json['loggingConfiguration']),
        ]);
    }

    private function populateResultImageLoggingConfiguration(array $json): ImageLoggingConfiguration
    {
        return new ImageLoggingConfiguration([
            'logGroupName' => isset($json['logGroupName']) ? (string) $json['logGroupName'] : null,
        ]);
    }

    private function populateResultImageRecipe(array $json): ImageRecipe
    {
        return new ImageRecipe([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'type' => isset($json['type']) ? (!ImageType::exists((string) $json['type']) ? ImageType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'platform' => isset($json['platform']) ? (!Platform::exists((string) $json['platform']) ? Platform::UNKNOWN_TO_SDK : (string) $json['platform']) : null,
            'owner' => isset($json['owner']) ? (string) $json['owner'] : null,
            'version' => isset($json['version']) ? (string) $json['version'] : null,
            'components' => !isset($json['components']) ? null : $this->populateResultComponentConfigurationList($json['components']),
            'parentImage' => isset($json['parentImage']) ? (string) $json['parentImage'] : null,
            'blockDeviceMappings' => !isset($json['blockDeviceMappings']) ? null : $this->populateResultInstanceBlockDeviceMappings($json['blockDeviceMappings']),
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'tags' => !isset($json['tags']) ? null : $this->populateResultTagMap($json['tags']),
            'workingDirectory' => isset($json['workingDirectory']) ? (string) $json['workingDirectory'] : null,
            'additionalInstanceConfiguration' => empty($json['additionalInstanceConfiguration']) ? null : $this->populateResultAdditionalInstanceConfiguration($json['additionalInstanceConfiguration']),
            'amiTags' => !isset($json['amiTags']) ? null : $this->populateResultTagMap($json['amiTags']),
        ]);
    }

    private function populateResultImageScanState(array $json): ImageScanState
    {
        return new ImageScanState([
            'status' => isset($json['status']) ? (!ImageScanStatus::exists((string) $json['status']) ? ImageScanStatus::UNKNOWN_TO_SDK : (string) $json['status']) : null,
            'reason' => isset($json['reason']) ? (string) $json['reason'] : null,
        ]);
    }

    private function populateResultImageScanningConfiguration(array $json): ImageScanningConfiguration
    {
        return new ImageScanningConfiguration([
            'imageScanningEnabled' => isset($json['imageScanningEnabled']) ? filter_var($json['imageScanningEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'ecrConfiguration' => empty($json['ecrConfiguration']) ? null : $this->populateResultEcrConfiguration($json['ecrConfiguration']),
        ]);
    }

    private function populateResultImageState(array $json): ImageState
    {
        return new ImageState([
            'status' => isset($json['status']) ? (!ImageStatus::exists((string) $json['status']) ? ImageStatus::UNKNOWN_TO_SDK : (string) $json['status']) : null,
            'reason' => isset($json['reason']) ? (string) $json['reason'] : null,
        ]);
    }

    private function populateResultImageTestsConfiguration(array $json): ImageTestsConfiguration
    {
        return new ImageTestsConfiguration([
            'imageTestsEnabled' => isset($json['imageTestsEnabled']) ? filter_var($json['imageTestsEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'timeoutMinutes' => isset($json['timeoutMinutes']) ? (int) $json['timeoutMinutes'] : null,
        ]);
    }

    private function populateResultInfrastructureConfiguration(array $json): InfrastructureConfiguration
    {
        return new InfrastructureConfiguration([
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'instanceTypes' => !isset($json['instanceTypes']) ? null : $this->populateResultInstanceTypeList($json['instanceTypes']),
            'instanceProfileName' => isset($json['instanceProfileName']) ? (string) $json['instanceProfileName'] : null,
            'securityGroupIds' => !isset($json['securityGroupIds']) ? null : $this->populateResultSecurityGroupIds($json['securityGroupIds']),
            'subnetId' => isset($json['subnetId']) ? (string) $json['subnetId'] : null,
            'logging' => empty($json['logging']) ? null : $this->populateResultLogging($json['logging']),
            'keyPair' => isset($json['keyPair']) ? (string) $json['keyPair'] : null,
            'terminateInstanceOnFailure' => isset($json['terminateInstanceOnFailure']) ? filter_var($json['terminateInstanceOnFailure'], \FILTER_VALIDATE_BOOLEAN) : null,
            'snsTopicArn' => isset($json['snsTopicArn']) ? (string) $json['snsTopicArn'] : null,
            'dateCreated' => isset($json['dateCreated']) ? (string) $json['dateCreated'] : null,
            'dateUpdated' => isset($json['dateUpdated']) ? (string) $json['dateUpdated'] : null,
            'resourceTags' => !isset($json['resourceTags']) ? null : $this->populateResultResourceTagMap($json['resourceTags']),
            'instanceMetadataOptions' => empty($json['instanceMetadataOptions']) ? null : $this->populateResultInstanceMetadataOptions($json['instanceMetadataOptions']),
            'tags' => !isset($json['tags']) ? null : $this->populateResultTagMap($json['tags']),
            'placement' => empty($json['placement']) ? null : $this->populateResultPlacement($json['placement']),
        ]);
    }

    private function populateResultInstanceBlockDeviceMapping(array $json): InstanceBlockDeviceMapping
    {
        return new InstanceBlockDeviceMapping([
            'deviceName' => isset($json['deviceName']) ? (string) $json['deviceName'] : null,
            'ebs' => empty($json['ebs']) ? null : $this->populateResultEbsInstanceBlockDeviceSpecification($json['ebs']),
            'virtualName' => isset($json['virtualName']) ? (string) $json['virtualName'] : null,
            'noDevice' => isset($json['noDevice']) ? (string) $json['noDevice'] : null,
        ]);
    }

    /**
     * @return InstanceBlockDeviceMapping[]
     */
    private function populateResultInstanceBlockDeviceMappings(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultInstanceBlockDeviceMapping($item);
        }

        return $items;
    }

    private function populateResultInstanceConfiguration(array $json): InstanceConfiguration
    {
        return new InstanceConfiguration([
            'image' => isset($json['image']) ? (string) $json['image'] : null,
            'blockDeviceMappings' => !isset($json['blockDeviceMappings']) ? null : $this->populateResultInstanceBlockDeviceMappings($json['blockDeviceMappings']),
        ]);
    }

    private function populateResultInstanceMetadataOptions(array $json): InstanceMetadataOptions
    {
        return new InstanceMetadataOptions([
            'httpTokens' => isset($json['httpTokens']) ? (string) $json['httpTokens'] : null,
            'httpPutResponseHopLimit' => isset($json['httpPutResponseHopLimit']) ? (int) $json['httpPutResponseHopLimit'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultInstanceTypeList(array $json): array
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

    private function populateResultLatestVersionReferences(array $json): LatestVersionReferences
    {
        return new LatestVersionReferences([
            'latestVersionArn' => isset($json['latestVersionArn']) ? (string) $json['latestVersionArn'] : null,
            'latestMajorVersionArn' => isset($json['latestMajorVersionArn']) ? (string) $json['latestMajorVersionArn'] : null,
            'latestMinorVersionArn' => isset($json['latestMinorVersionArn']) ? (string) $json['latestMinorVersionArn'] : null,
            'latestPatchVersionArn' => isset($json['latestPatchVersionArn']) ? (string) $json['latestPatchVersionArn'] : null,
        ]);
    }

    private function populateResultLaunchPermissionConfiguration(array $json): LaunchPermissionConfiguration
    {
        return new LaunchPermissionConfiguration([
            'userIds' => !isset($json['userIds']) ? null : $this->populateResultAccountList($json['userIds']),
            'userGroups' => !isset($json['userGroups']) ? null : $this->populateResultStringList($json['userGroups']),
            'organizationArns' => !isset($json['organizationArns']) ? null : $this->populateResultOrganizationArnList($json['organizationArns']),
            'organizationalUnitArns' => !isset($json['organizationalUnitArns']) ? null : $this->populateResultOrganizationalUnitArnList($json['organizationalUnitArns']),
        ]);
    }

    private function populateResultLaunchTemplateConfiguration(array $json): LaunchTemplateConfiguration
    {
        return new LaunchTemplateConfiguration([
            'launchTemplateId' => (string) $json['launchTemplateId'],
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
            'setDefaultVersion' => isset($json['setDefaultVersion']) ? filter_var($json['setDefaultVersion'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return LaunchTemplateConfiguration[]
     */
    private function populateResultLaunchTemplateConfigurationList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultLaunchTemplateConfiguration($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultLicenseConfigurationArnList(array $json): array
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

    private function populateResultLogging(array $json): Logging
    {
        return new Logging([
            's3Logs' => empty($json['s3Logs']) ? null : $this->populateResultS3Logs($json['s3Logs']),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultOrganizationArnList(array $json): array
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
    private function populateResultOrganizationalUnitArnList(array $json): array
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

    private function populateResultOutputResources(array $json): OutputResources
    {
        return new OutputResources([
            'amis' => !isset($json['amis']) ? null : $this->populateResultAmiList($json['amis']),
            'containers' => !isset($json['containers']) ? null : $this->populateResultContainerList($json['containers']),
        ]);
    }

    private function populateResultPlacement(array $json): Placement
    {
        return new Placement([
            'availabilityZone' => isset($json['availabilityZone']) ? (string) $json['availabilityZone'] : null,
            'tenancy' => isset($json['tenancy']) ? (!TenancyType::exists((string) $json['tenancy']) ? TenancyType::UNKNOWN_TO_SDK : (string) $json['tenancy']) : null,
            'hostId' => isset($json['hostId']) ? (string) $json['hostId'] : null,
            'hostResourceGroupArn' => isset($json['hostResourceGroupArn']) ? (string) $json['hostResourceGroupArn'] : null,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultResourceTagMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultS3ExportConfiguration(array $json): S3ExportConfiguration
    {
        return new S3ExportConfiguration([
            'roleName' => (string) $json['roleName'],
            'diskImageFormat' => !DiskImageFormat::exists((string) $json['diskImageFormat']) ? DiskImageFormat::UNKNOWN_TO_SDK : (string) $json['diskImageFormat'],
            's3Bucket' => (string) $json['s3Bucket'],
            's3Prefix' => isset($json['s3Prefix']) ? (string) $json['s3Prefix'] : null,
        ]);
    }

    private function populateResultS3Logs(array $json): S3Logs
    {
        return new S3Logs([
            's3BucketName' => isset($json['s3BucketName']) ? (string) $json['s3BucketName'] : null,
            's3KeyPrefix' => isset($json['s3KeyPrefix']) ? (string) $json['s3KeyPrefix'] : null,
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

    private function populateResultSsmParameterConfiguration(array $json): SsmParameterConfiguration
    {
        return new SsmParameterConfiguration([
            'amiAccountId' => isset($json['amiAccountId']) ? (string) $json['amiAccountId'] : null,
            'parameterName' => (string) $json['parameterName'],
            'dataType' => isset($json['dataType']) ? (!SsmParameterDataType::exists((string) $json['dataType']) ? SsmParameterDataType::UNKNOWN_TO_SDK : (string) $json['dataType']) : null,
        ]);
    }

    /**
     * @return SsmParameterConfiguration[]
     */
    private function populateResultSsmParameterConfigurationList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSsmParameterConfiguration($item);
        }

        return $items;
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

    private function populateResultSystemsManagerAgent(array $json): SystemsManagerAgent
    {
        return new SystemsManagerAgent([
            'uninstallAfterBuild' => isset($json['uninstallAfterBuild']) ? filter_var($json['uninstallAfterBuild'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultTagMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultTargetContainerRepository(array $json): TargetContainerRepository
    {
        return new TargetContainerRepository([
            'service' => !ContainerRepositoryService::exists((string) $json['service']) ? ContainerRepositoryService::UNKNOWN_TO_SDK : (string) $json['service'],
            'repositoryName' => (string) $json['repositoryName'],
        ]);
    }

    private function populateResultWorkflowConfiguration(array $json): WorkflowConfiguration
    {
        return new WorkflowConfiguration([
            'workflowArn' => (string) $json['workflowArn'],
            'parameters' => !isset($json['parameters']) ? null : $this->populateResultWorkflowParameterList($json['parameters']),
            'parallelGroup' => isset($json['parallelGroup']) ? (string) $json['parallelGroup'] : null,
            'onFailure' => isset($json['onFailure']) ? (!OnWorkflowFailure::exists((string) $json['onFailure']) ? OnWorkflowFailure::UNKNOWN_TO_SDK : (string) $json['onFailure']) : null,
        ]);
    }

    /**
     * @return WorkflowConfiguration[]
     */
    private function populateResultWorkflowConfigurationList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultWorkflowConfiguration($item);
        }

        return $items;
    }

    private function populateResultWorkflowParameter(array $json): WorkflowParameter
    {
        return new WorkflowParameter([
            'name' => (string) $json['name'],
            'value' => $this->populateResultWorkflowParameterValueList($json['value']),
        ]);
    }

    /**
     * @return WorkflowParameter[]
     */
    private function populateResultWorkflowParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultWorkflowParameter($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultWorkflowParameterValueList(array $json): array
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
}
