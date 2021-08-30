<?php

namespace AsyncAws\ElastiCache\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\ElastiCache\ElastiCacheClient;
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
     */
    private $marker;

    /**
     * A list of clusters. Each item in the list contains detailed information about one cluster.
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
            if ($page->marker) {
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

        $this->marker = ($v = $data->Marker) ? (string) $v : null;
        $this->cacheClusters = !$data->CacheClusters ? [] : $this->populateResultCacheClusterList($data->CacheClusters);
    }

    /**
     * @return CacheCluster[]
     */
    private function populateResultCacheClusterList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheCluster as $item) {
            $items[] = new CacheCluster([
                'CacheClusterId' => ($v = $item->CacheClusterId) ? (string) $v : null,
                'ConfigurationEndpoint' => !$item->ConfigurationEndpoint ? null : new Endpoint([
                    'Address' => ($v = $item->ConfigurationEndpoint->Address) ? (string) $v : null,
                    'Port' => ($v = $item->ConfigurationEndpoint->Port) ? (int) (string) $v : null,
                ]),
                'ClientDownloadLandingPage' => ($v = $item->ClientDownloadLandingPage) ? (string) $v : null,
                'CacheNodeType' => ($v = $item->CacheNodeType) ? (string) $v : null,
                'Engine' => ($v = $item->Engine) ? (string) $v : null,
                'EngineVersion' => ($v = $item->EngineVersion) ? (string) $v : null,
                'CacheClusterStatus' => ($v = $item->CacheClusterStatus) ? (string) $v : null,
                'NumCacheNodes' => ($v = $item->NumCacheNodes) ? (int) (string) $v : null,
                'PreferredAvailabilityZone' => ($v = $item->PreferredAvailabilityZone) ? (string) $v : null,
                'PreferredOutpostArn' => ($v = $item->PreferredOutpostArn) ? (string) $v : null,
                'CacheClusterCreateTime' => ($v = $item->CacheClusterCreateTime) ? new \DateTimeImmutable((string) $v) : null,
                'PreferredMaintenanceWindow' => ($v = $item->PreferredMaintenanceWindow) ? (string) $v : null,
                'PendingModifiedValues' => !$item->PendingModifiedValues ? null : new PendingModifiedValues([
                    'NumCacheNodes' => ($v = $item->PendingModifiedValues->NumCacheNodes) ? (int) (string) $v : null,
                    'CacheNodeIdsToRemove' => !$item->PendingModifiedValues->CacheNodeIdsToRemove ? null : $this->populateResultCacheNodeIdsList($item->PendingModifiedValues->CacheNodeIdsToRemove),
                    'EngineVersion' => ($v = $item->PendingModifiedValues->EngineVersion) ? (string) $v : null,
                    'CacheNodeType' => ($v = $item->PendingModifiedValues->CacheNodeType) ? (string) $v : null,
                    'AuthTokenStatus' => ($v = $item->PendingModifiedValues->AuthTokenStatus) ? (string) $v : null,
                    'LogDeliveryConfigurations' => !$item->PendingModifiedValues->LogDeliveryConfigurations ? null : $this->populateResultPendingLogDeliveryConfigurationList($item->PendingModifiedValues->LogDeliveryConfigurations),
                ]),
                'NotificationConfiguration' => !$item->NotificationConfiguration ? null : new NotificationConfiguration([
                    'TopicArn' => ($v = $item->NotificationConfiguration->TopicArn) ? (string) $v : null,
                    'TopicStatus' => ($v = $item->NotificationConfiguration->TopicStatus) ? (string) $v : null,
                ]),
                'CacheSecurityGroups' => !$item->CacheSecurityGroups ? null : $this->populateResultCacheSecurityGroupMembershipList($item->CacheSecurityGroups),
                'CacheParameterGroup' => !$item->CacheParameterGroup ? null : new CacheParameterGroupStatus([
                    'CacheParameterGroupName' => ($v = $item->CacheParameterGroup->CacheParameterGroupName) ? (string) $v : null,
                    'ParameterApplyStatus' => ($v = $item->CacheParameterGroup->ParameterApplyStatus) ? (string) $v : null,
                    'CacheNodeIdsToReboot' => !$item->CacheParameterGroup->CacheNodeIdsToReboot ? null : $this->populateResultCacheNodeIdsList($item->CacheParameterGroup->CacheNodeIdsToReboot),
                ]),
                'CacheSubnetGroupName' => ($v = $item->CacheSubnetGroupName) ? (string) $v : null,
                'CacheNodes' => !$item->CacheNodes ? null : $this->populateResultCacheNodeList($item->CacheNodes),
                'AutoMinorVersionUpgrade' => ($v = $item->AutoMinorVersionUpgrade) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'SecurityGroups' => !$item->SecurityGroups ? null : $this->populateResultSecurityGroupMembershipList($item->SecurityGroups),
                'ReplicationGroupId' => ($v = $item->ReplicationGroupId) ? (string) $v : null,
                'SnapshotRetentionLimit' => ($v = $item->SnapshotRetentionLimit) ? (int) (string) $v : null,
                'SnapshotWindow' => ($v = $item->SnapshotWindow) ? (string) $v : null,
                'AuthTokenEnabled' => ($v = $item->AuthTokenEnabled) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'AuthTokenLastModifiedDate' => ($v = $item->AuthTokenLastModifiedDate) ? new \DateTimeImmutable((string) $v) : null,
                'TransitEncryptionEnabled' => ($v = $item->TransitEncryptionEnabled) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'AtRestEncryptionEnabled' => ($v = $item->AtRestEncryptionEnabled) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'ARN' => ($v = $item->ARN) ? (string) $v : null,
                'ReplicationGroupLogDeliveryEnabled' => ($v = $item->ReplicationGroupLogDeliveryEnabled) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'LogDeliveryConfigurations' => !$item->LogDeliveryConfigurations ? null : $this->populateResultLogDeliveryConfigurationList($item->LogDeliveryConfigurations),
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultCacheNodeIdsList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheNodeId as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
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
            $items[] = new CacheNode([
                'CacheNodeId' => ($v = $item->CacheNodeId) ? (string) $v : null,
                'CacheNodeStatus' => ($v = $item->CacheNodeStatus) ? (string) $v : null,
                'CacheNodeCreateTime' => ($v = $item->CacheNodeCreateTime) ? new \DateTimeImmutable((string) $v) : null,
                'Endpoint' => !$item->Endpoint ? null : new Endpoint([
                    'Address' => ($v = $item->Endpoint->Address) ? (string) $v : null,
                    'Port' => ($v = $item->Endpoint->Port) ? (int) (string) $v : null,
                ]),
                'ParameterGroupStatus' => ($v = $item->ParameterGroupStatus) ? (string) $v : null,
                'SourceCacheNodeId' => ($v = $item->SourceCacheNodeId) ? (string) $v : null,
                'CustomerAvailabilityZone' => ($v = $item->CustomerAvailabilityZone) ? (string) $v : null,
                'CustomerOutpostArn' => ($v = $item->CustomerOutpostArn) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return CacheSecurityGroupMembership[]
     */
    private function populateResultCacheSecurityGroupMembershipList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->CacheSecurityGroup as $item) {
            $items[] = new CacheSecurityGroupMembership([
                'CacheSecurityGroupName' => ($v = $item->CacheSecurityGroupName) ? (string) $v : null,
                'Status' => ($v = $item->Status) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return LogDeliveryConfiguration[]
     */
    private function populateResultLogDeliveryConfigurationList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->LogDeliveryConfiguration as $item) {
            $items[] = new LogDeliveryConfiguration([
                'LogType' => ($v = $item->LogType) ? (string) $v : null,
                'DestinationType' => ($v = $item->DestinationType) ? (string) $v : null,
                'DestinationDetails' => !$item->DestinationDetails ? null : new DestinationDetails([
                    'CloudWatchLogsDetails' => !$item->DestinationDetails->CloudWatchLogsDetails ? null : new CloudWatchLogsDestinationDetails([
                        'LogGroup' => ($v = $item->DestinationDetails->CloudWatchLogsDetails->LogGroup) ? (string) $v : null,
                    ]),
                    'KinesisFirehoseDetails' => !$item->DestinationDetails->KinesisFirehoseDetails ? null : new KinesisFirehoseDestinationDetails([
                        'DeliveryStream' => ($v = $item->DestinationDetails->KinesisFirehoseDetails->DeliveryStream) ? (string) $v : null,
                    ]),
                ]),
                'LogFormat' => ($v = $item->LogFormat) ? (string) $v : null,
                'Status' => ($v = $item->Status) ? (string) $v : null,
                'Message' => ($v = $item->Message) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return PendingLogDeliveryConfiguration[]
     */
    private function populateResultPendingLogDeliveryConfigurationList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new PendingLogDeliveryConfiguration([
                'LogType' => ($v = $item->LogType) ? (string) $v : null,
                'DestinationType' => ($v = $item->DestinationType) ? (string) $v : null,
                'DestinationDetails' => !$item->DestinationDetails ? null : new DestinationDetails([
                    'CloudWatchLogsDetails' => !$item->DestinationDetails->CloudWatchLogsDetails ? null : new CloudWatchLogsDestinationDetails([
                        'LogGroup' => ($v = $item->DestinationDetails->CloudWatchLogsDetails->LogGroup) ? (string) $v : null,
                    ]),
                    'KinesisFirehoseDetails' => !$item->DestinationDetails->KinesisFirehoseDetails ? null : new KinesisFirehoseDestinationDetails([
                        'DeliveryStream' => ($v = $item->DestinationDetails->KinesisFirehoseDetails->DeliveryStream) ? (string) $v : null,
                    ]),
                ]),
                'LogFormat' => ($v = $item->LogFormat) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return SecurityGroupMembership[]
     */
    private function populateResultSecurityGroupMembershipList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new SecurityGroupMembership([
                'SecurityGroupId' => ($v = $item->SecurityGroupId) ? (string) $v : null,
                'Status' => ($v = $item->Status) ? (string) $v : null,
            ]);
        }

        return $items;
    }
}
