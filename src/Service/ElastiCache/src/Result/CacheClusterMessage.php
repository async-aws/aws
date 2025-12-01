<?php

namespace AsyncAws\ElastiCache\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\ElastiCache\ElastiCacheClient;
use AsyncAws\ElastiCache\Enum\AuthTokenUpdateStatus;
use AsyncAws\ElastiCache\Enum\DestinationType;
use AsyncAws\ElastiCache\Enum\IpDiscovery;
use AsyncAws\ElastiCache\Enum\LogDeliveryConfigurationStatus;
use AsyncAws\ElastiCache\Enum\LogFormat;
use AsyncAws\ElastiCache\Enum\LogType;
use AsyncAws\ElastiCache\Enum\NetworkType;
use AsyncAws\ElastiCache\Enum\TransitEncryptionMode;
use AsyncAws\ElastiCache\Input\DescribeCacheClustersMessage;
use AsyncAws\ElastiCache\ValueObject\CacheCluster;
use AsyncAws\ElastiCache\ValueObject\CacheNode;
use AsyncAws\ElastiCache\ValueObject\CacheParameterGroupStatus;
use AsyncAws\ElastiCache\ValueObject\CacheSecurityGroupMembership;
use AsyncAws\ElastiCache\ValueObject\CloudWatchLogsDestinationDetails;
use AsyncAws\ElastiCache\ValueObject\DestinationDetails;
use AsyncAws\ElastiCache\ValueObject\Endpoint;
use AsyncAws\ElastiCache\ValueObject\KinesisFirehoseDestinationDetails;
use AsyncAws\ElastiCache\ValueObject\LogDeliveryConfiguration;
use AsyncAws\ElastiCache\ValueObject\NotificationConfiguration;
use AsyncAws\ElastiCache\ValueObject\PendingLogDeliveryConfiguration;
use AsyncAws\ElastiCache\ValueObject\PendingModifiedValues;
use AsyncAws\ElastiCache\ValueObject\ScaleConfig;
use AsyncAws\ElastiCache\ValueObject\SecurityGroupMembership;

/**
 * Represents the output of a `DescribeCacheClusters` operation.
 *
 * @implements \IteratorAggregate<CacheCluster>
 */
class CacheClusterMessage extends Result implements \IteratorAggregate
{
    /**
     * Provides an identifier to allow retrieval of paginated results.
     *
     * @var string|null
     */
    private $marker;

    /**
     * A list of clusters. Each item in the list contains detailed information about one cluster.
     *
     * @var CacheCluster[]
     */
    private $cacheClusters;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<CacheCluster>
     */
    public function getCacheClusters(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->cacheClusters;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof ElastiCacheClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeCacheClustersMessage) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->marker) {
                $input->setMarker($page->marker);

                $this->registerPrefetch($nextPage = $client->describeCacheClusters($input));
            } else {
                $nextPage = null;
            }

            yield from $page->cacheClusters;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over CacheClusters.
     *
     * @return \Traversable<CacheCluster>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getCacheClusters();
    }

    public function getMarker(): ?string
    {
        $this->initialize();

        return $this->marker;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->DescribeCacheClustersResult;

        $this->marker = (null !== $v = $data->Marker[0]) ? (string) $v : null;
        $this->cacheClusters = (0 === ($v = $data->CacheClusters)->count()) ? [] : $this->populateResultCacheClusterList($v);
    }

    private function populateResultCacheCluster(\SimpleXMLElement $xml): CacheCluster
    {
        return new CacheCluster([
            'CacheClusterId' => (null !== $v = $xml->CacheClusterId[0]) ? (string) $v : null,
            'ConfigurationEndpoint' => 0 === $xml->ConfigurationEndpoint->count() ? null : $this->populateResultEndpoint($xml->ConfigurationEndpoint),
            'ClientDownloadLandingPage' => (null !== $v = $xml->ClientDownloadLandingPage[0]) ? (string) $v : null,
            'CacheNodeType' => (null !== $v = $xml->CacheNodeType[0]) ? (string) $v : null,
            'Engine' => (null !== $v = $xml->Engine[0]) ? (string) $v : null,
            'EngineVersion' => (null !== $v = $xml->EngineVersion[0]) ? (string) $v : null,
            'CacheClusterStatus' => (null !== $v = $xml->CacheClusterStatus[0]) ? (string) $v : null,
            'NumCacheNodes' => (null !== $v = $xml->NumCacheNodes[0]) ? (int) (string) $v : null,
            'PreferredAvailabilityZone' => (null !== $v = $xml->PreferredAvailabilityZone[0]) ? (string) $v : null,
            'PreferredOutpostArn' => (null !== $v = $xml->PreferredOutpostArn[0]) ? (string) $v : null,
            'CacheClusterCreateTime' => (null !== $v = $xml->CacheClusterCreateTime[0]) ? new \DateTimeImmutable((string) $v) : null,
            'PreferredMaintenanceWindow' => (null !== $v = $xml->PreferredMaintenanceWindow[0]) ? (string) $v : null,
            'PendingModifiedValues' => 0 === $xml->PendingModifiedValues->count() ? null : $this->populateResultPendingModifiedValues($xml->PendingModifiedValues),
            'NotificationConfiguration' => 0 === $xml->NotificationConfiguration->count() ? null : $this->populateResultNotificationConfiguration($xml->NotificationConfiguration),
            'CacheSecurityGroups' => (0 === ($v = $xml->CacheSecurityGroups)->count()) ? null : $this->populateResultCacheSecurityGroupMembershipList($v),
            'CacheParameterGroup' => 0 === $xml->CacheParameterGroup->count() ? null : $this->populateResultCacheParameterGroupStatus($xml->CacheParameterGroup),
            'CacheSubnetGroupName' => (null !== $v = $xml->CacheSubnetGroupName[0]) ? (string) $v : null,
            'CacheNodes' => (0 === ($v = $xml->CacheNodes)->count()) ? null : $this->populateResultCacheNodeList($v),
            'AutoMinorVersionUpgrade' => (null !== $v = $xml->AutoMinorVersionUpgrade[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'SecurityGroups' => (0 === ($v = $xml->SecurityGroups)->count()) ? null : $this->populateResultSecurityGroupMembershipList($v),
            'ReplicationGroupId' => (null !== $v = $xml->ReplicationGroupId[0]) ? (string) $v : null,
            'SnapshotRetentionLimit' => (null !== $v = $xml->SnapshotRetentionLimit[0]) ? (int) (string) $v : null,
            'SnapshotWindow' => (null !== $v = $xml->SnapshotWindow[0]) ? (string) $v : null,
            'AuthTokenEnabled' => (null !== $v = $xml->AuthTokenEnabled[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'AuthTokenLastModifiedDate' => (null !== $v = $xml->AuthTokenLastModifiedDate[0]) ? new \DateTimeImmutable((string) $v) : null,
            'TransitEncryptionEnabled' => (null !== $v = $xml->TransitEncryptionEnabled[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'AtRestEncryptionEnabled' => (null !== $v = $xml->AtRestEncryptionEnabled[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'ARN' => (null !== $v = $xml->ARN[0]) ? (string) $v : null,
            'ReplicationGroupLogDeliveryEnabled' => (null !== $v = $xml->ReplicationGroupLogDeliveryEnabled[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'LogDeliveryConfigurations' => (0 === ($v = $xml->LogDeliveryConfigurations)->count()) ? null : $this->populateResultLogDeliveryConfigurationList($v),
            'NetworkType' => (null !== $v = $xml->NetworkType[0]) ? (!NetworkType::exists((string) $xml->NetworkType) ? NetworkType::UNKNOWN_TO_SDK : (string) $xml->NetworkType) : null,
            'IpDiscovery' => (null !== $v = $xml->IpDiscovery[0]) ? (!IpDiscovery::exists((string) $xml->IpDiscovery) ? IpDiscovery::UNKNOWN_TO_SDK : (string) $xml->IpDiscovery) : null,
            'TransitEncryptionMode' => (null !== $v = $xml->TransitEncryptionMode[0]) ? (!TransitEncryptionMode::exists((string) $xml->TransitEncryptionMode) ? TransitEncryptionMode::UNKNOWN_TO_SDK : (string) $xml->TransitEncryptionMode) : null,
        ]);
    }

    /**
     * @return CacheCluster[]
     */
    private function populateResultCacheClusterList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheCluster as $item) {
            $items[] = $this->populateResultCacheCluster($item);
        }

        return $items;
    }

    private function populateResultCacheNode(\SimpleXMLElement $xml): CacheNode
    {
        return new CacheNode([
            'CacheNodeId' => (null !== $v = $xml->CacheNodeId[0]) ? (string) $v : null,
            'CacheNodeStatus' => (null !== $v = $xml->CacheNodeStatus[0]) ? (string) $v : null,
            'CacheNodeCreateTime' => (null !== $v = $xml->CacheNodeCreateTime[0]) ? new \DateTimeImmutable((string) $v) : null,
            'Endpoint' => 0 === $xml->Endpoint->count() ? null : $this->populateResultEndpoint($xml->Endpoint),
            'ParameterGroupStatus' => (null !== $v = $xml->ParameterGroupStatus[0]) ? (string) $v : null,
            'SourceCacheNodeId' => (null !== $v = $xml->SourceCacheNodeId[0]) ? (string) $v : null,
            'CustomerAvailabilityZone' => (null !== $v = $xml->CustomerAvailabilityZone[0]) ? (string) $v : null,
            'CustomerOutpostArn' => (null !== $v = $xml->CustomerOutpostArn[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultCacheNodeIdsList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheNodeId as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }

    /**
     * @return CacheNode[]
     */
    private function populateResultCacheNodeList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheNode as $item) {
            $items[] = $this->populateResultCacheNode($item);
        }

        return $items;
    }

    private function populateResultCacheParameterGroupStatus(\SimpleXMLElement $xml): CacheParameterGroupStatus
    {
        return new CacheParameterGroupStatus([
            'CacheParameterGroupName' => (null !== $v = $xml->CacheParameterGroupName[0]) ? (string) $v : null,
            'ParameterApplyStatus' => (null !== $v = $xml->ParameterApplyStatus[0]) ? (string) $v : null,
            'CacheNodeIdsToReboot' => (0 === ($v = $xml->CacheNodeIdsToReboot)->count()) ? null : $this->populateResultCacheNodeIdsList($v),
        ]);
    }

    private function populateResultCacheSecurityGroupMembership(\SimpleXMLElement $xml): CacheSecurityGroupMembership
    {
        return new CacheSecurityGroupMembership([
            'CacheSecurityGroupName' => (null !== $v = $xml->CacheSecurityGroupName[0]) ? (string) $v : null,
            'Status' => (null !== $v = $xml->Status[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return CacheSecurityGroupMembership[]
     */
    private function populateResultCacheSecurityGroupMembershipList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheSecurityGroup as $item) {
            $items[] = $this->populateResultCacheSecurityGroupMembership($item);
        }

        return $items;
    }

    private function populateResultCloudWatchLogsDestinationDetails(\SimpleXMLElement $xml): CloudWatchLogsDestinationDetails
    {
        return new CloudWatchLogsDestinationDetails([
            'LogGroup' => (null !== $v = $xml->LogGroup[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultDestinationDetails(\SimpleXMLElement $xml): DestinationDetails
    {
        return new DestinationDetails([
            'CloudWatchLogsDetails' => 0 === $xml->CloudWatchLogsDetails->count() ? null : $this->populateResultCloudWatchLogsDestinationDetails($xml->CloudWatchLogsDetails),
            'KinesisFirehoseDetails' => 0 === $xml->KinesisFirehoseDetails->count() ? null : $this->populateResultKinesisFirehoseDestinationDetails($xml->KinesisFirehoseDetails),
        ]);
    }

    private function populateResultEndpoint(\SimpleXMLElement $xml): Endpoint
    {
        return new Endpoint([
            'Address' => (null !== $v = $xml->Address[0]) ? (string) $v : null,
            'Port' => (null !== $v = $xml->Port[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultKinesisFirehoseDestinationDetails(\SimpleXMLElement $xml): KinesisFirehoseDestinationDetails
    {
        return new KinesisFirehoseDestinationDetails([
            'DeliveryStream' => (null !== $v = $xml->DeliveryStream[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultLogDeliveryConfiguration(\SimpleXMLElement $xml): LogDeliveryConfiguration
    {
        return new LogDeliveryConfiguration([
            'LogType' => (null !== $v = $xml->LogType[0]) ? (!LogType::exists((string) $xml->LogType) ? LogType::UNKNOWN_TO_SDK : (string) $xml->LogType) : null,
            'DestinationType' => (null !== $v = $xml->DestinationType[0]) ? (!DestinationType::exists((string) $xml->DestinationType) ? DestinationType::UNKNOWN_TO_SDK : (string) $xml->DestinationType) : null,
            'DestinationDetails' => 0 === $xml->DestinationDetails->count() ? null : $this->populateResultDestinationDetails($xml->DestinationDetails),
            'LogFormat' => (null !== $v = $xml->LogFormat[0]) ? (!LogFormat::exists((string) $xml->LogFormat) ? LogFormat::UNKNOWN_TO_SDK : (string) $xml->LogFormat) : null,
            'Status' => (null !== $v = $xml->Status[0]) ? (!LogDeliveryConfigurationStatus::exists((string) $xml->Status) ? LogDeliveryConfigurationStatus::UNKNOWN_TO_SDK : (string) $xml->Status) : null,
            'Message' => (null !== $v = $xml->Message[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return LogDeliveryConfiguration[]
     */
    private function populateResultLogDeliveryConfigurationList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->LogDeliveryConfiguration as $item) {
            $items[] = $this->populateResultLogDeliveryConfiguration($item);
        }

        return $items;
    }

    private function populateResultNotificationConfiguration(\SimpleXMLElement $xml): NotificationConfiguration
    {
        return new NotificationConfiguration([
            'TopicArn' => (null !== $v = $xml->TopicArn[0]) ? (string) $v : null,
            'TopicStatus' => (null !== $v = $xml->TopicStatus[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultPendingLogDeliveryConfiguration(\SimpleXMLElement $xml): PendingLogDeliveryConfiguration
    {
        return new PendingLogDeliveryConfiguration([
            'LogType' => (null !== $v = $xml->LogType[0]) ? (!LogType::exists((string) $xml->LogType) ? LogType::UNKNOWN_TO_SDK : (string) $xml->LogType) : null,
            'DestinationType' => (null !== $v = $xml->DestinationType[0]) ? (!DestinationType::exists((string) $xml->DestinationType) ? DestinationType::UNKNOWN_TO_SDK : (string) $xml->DestinationType) : null,
            'DestinationDetails' => 0 === $xml->DestinationDetails->count() ? null : $this->populateResultDestinationDetails($xml->DestinationDetails),
            'LogFormat' => (null !== $v = $xml->LogFormat[0]) ? (!LogFormat::exists((string) $xml->LogFormat) ? LogFormat::UNKNOWN_TO_SDK : (string) $xml->LogFormat) : null,
        ]);
    }

    /**
     * @return PendingLogDeliveryConfiguration[]
     */
    private function populateResultPendingLogDeliveryConfigurationList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultPendingLogDeliveryConfiguration($item);
        }

        return $items;
    }

    private function populateResultPendingModifiedValues(\SimpleXMLElement $xml): PendingModifiedValues
    {
        return new PendingModifiedValues([
            'NumCacheNodes' => (null !== $v = $xml->NumCacheNodes[0]) ? (int) (string) $v : null,
            'CacheNodeIdsToRemove' => (0 === ($v = $xml->CacheNodeIdsToRemove)->count()) ? null : $this->populateResultCacheNodeIdsList($v),
            'EngineVersion' => (null !== $v = $xml->EngineVersion[0]) ? (string) $v : null,
            'CacheNodeType' => (null !== $v = $xml->CacheNodeType[0]) ? (string) $v : null,
            'AuthTokenStatus' => (null !== $v = $xml->AuthTokenStatus[0]) ? (!AuthTokenUpdateStatus::exists((string) $xml->AuthTokenStatus) ? AuthTokenUpdateStatus::UNKNOWN_TO_SDK : (string) $xml->AuthTokenStatus) : null,
            'LogDeliveryConfigurations' => (0 === ($v = $xml->LogDeliveryConfigurations)->count()) ? null : $this->populateResultPendingLogDeliveryConfigurationList($v),
            'TransitEncryptionEnabled' => (null !== $v = $xml->TransitEncryptionEnabled[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'TransitEncryptionMode' => (null !== $v = $xml->TransitEncryptionMode[0]) ? (!TransitEncryptionMode::exists((string) $xml->TransitEncryptionMode) ? TransitEncryptionMode::UNKNOWN_TO_SDK : (string) $xml->TransitEncryptionMode) : null,
            'ScaleConfig' => 0 === $xml->ScaleConfig->count() ? null : $this->populateResultScaleConfig($xml->ScaleConfig),
        ]);
    }

    private function populateResultScaleConfig(\SimpleXMLElement $xml): ScaleConfig
    {
        return new ScaleConfig([
            'ScalePercentage' => (null !== $v = $xml->ScalePercentage[0]) ? (int) (string) $v : null,
            'ScaleIntervalMinutes' => (null !== $v = $xml->ScaleIntervalMinutes[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultSecurityGroupMembership(\SimpleXMLElement $xml): SecurityGroupMembership
    {
        return new SecurityGroupMembership([
            'SecurityGroupId' => (null !== $v = $xml->SecurityGroupId[0]) ? (string) $v : null,
            'Status' => (null !== $v = $xml->Status[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return SecurityGroupMembership[]
     */
    private function populateResultSecurityGroupMembershipList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultSecurityGroupMembership($item);
        }

        return $items;
    }
}
