<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Contains all of the attributes of a specific cluster.
 */
final class CacheCluster
{
    /**
     * The user-supplied identifier of the cluster. This identifier is a unique key that identifies a cluster.
     */
    private $cacheClusterId;

    /**
     * Represents a Memcached cluster endpoint which can be used by an application to connect to any node in the cluster.
     * The configuration endpoint will always have `.cfg` in it.
     */
    private $configurationEndpoint;

    /**
     * The URL of the web page where you can download the latest ElastiCache client library.
     */
    private $clientDownloadLandingPage;

    /**
     * The name of the compute and memory capacity node type for the cluster.
     */
    private $cacheNodeType;

    /**
     * The name of the cache engine (`memcached` or `redis`) to be used for this cluster.
     */
    private $engine;

    /**
     * The version of the cache engine that is used in this cluster.
     */
    private $engineVersion;

    /**
     * The current state of this cluster, one of the following values: `available`, `creating`, `deleted`, `deleting`,
     * `incompatible-network`, `modifying`, `rebooting cluster nodes`, `restore-failed`, or `snapshotting`.
     */
    private $cacheClusterStatus;

    /**
     * The number of cache nodes in the cluster.
     */
    private $numCacheNodes;

    /**
     * The name of the Availability Zone in which the cluster is located or "Multiple" if the cache nodes are located in
     * different Availability Zones.
     */
    private $preferredAvailabilityZone;

    /**
     * The outpost ARN in which the cache cluster is created.
     */
    private $preferredOutpostArn;

    /**
     * The date and time when the cluster was created.
     */
    private $cacheClusterCreateTime;

    /**
     * Specifies the weekly time range during which maintenance on the cluster is performed. It is specified as a range in
     * the format ddd:hh24:mi-ddd:hh24:mi (24H Clock UTC). The minimum maintenance window is a 60 minute period.
     */
    private $preferredMaintenanceWindow;

    private $pendingModifiedValues;

    /**
     * Describes a notification topic and its status. Notification topics are used for publishing ElastiCache events to
     * subscribers using Amazon Simple Notification Service (SNS).
     */
    private $notificationConfiguration;

    /**
     * A list of cache security group elements, composed of name and status sub-elements.
     */
    private $cacheSecurityGroups;

    /**
     * Status of the cache parameter group.
     */
    private $cacheParameterGroup;

    /**
     * The name of the cache subnet group associated with the cluster.
     */
    private $cacheSubnetGroupName;

    /**
     * A list of cache nodes that are members of the cluster.
     */
    private $cacheNodes;

    /**
     *  If you are running Redis engine version 6.0 or later, set this parameter to yes if you want to opt-in to the next
     * auto minor version upgrade campaign. This parameter is disabled for previous versions.
     */
    private $autoMinorVersionUpgrade;

    /**
     * A list of VPC Security Groups associated with the cluster.
     */
    private $securityGroups;

    /**
     * The replication group to which this cluster belongs. If this field is empty, the cluster is not associated with any
     * replication group.
     */
    private $replicationGroupId;

    /**
     * The number of days for which ElastiCache retains automatic cluster snapshots before deleting them. For example, if
     * you set `SnapshotRetentionLimit` to 5, a snapshot that was taken today is retained for 5 days before being deleted.
     */
    private $snapshotRetentionLimit;

    /**
     * The daily time range (in UTC) during which ElastiCache begins taking a daily snapshot of your cluster.
     */
    private $snapshotWindow;

    /**
     * A flag that enables using an `AuthToken` (password) when issuing Redis commands.
     */
    private $authTokenEnabled;

    /**
     * The date the auth token was last modified.
     */
    private $authTokenLastModifiedDate;

    /**
     * A flag that enables in-transit encryption when set to `true`.
     */
    private $transitEncryptionEnabled;

    /**
     * A flag that enables encryption at-rest when set to `true`.
     */
    private $atRestEncryptionEnabled;

    /**
     * The ARN (Amazon Resource Name) of the cache cluster.
     */
    private $arn;

    /**
     * A boolean value indicating whether log delivery is enabled for the replication group.
     */
    private $replicationGroupLogDeliveryEnabled;

    /**
     * Returns the destination, format and type of the logs.
     */
    private $logDeliveryConfigurations;

    /**
     * @param array{
     *   CacheClusterId?: null|string,
     *   ConfigurationEndpoint?: null|Endpoint|array,
     *   ClientDownloadLandingPage?: null|string,
     *   CacheNodeType?: null|string,
     *   Engine?: null|string,
     *   EngineVersion?: null|string,
     *   CacheClusterStatus?: null|string,
     *   NumCacheNodes?: null|int,
     *   PreferredAvailabilityZone?: null|string,
     *   PreferredOutpostArn?: null|string,
     *   CacheClusterCreateTime?: null|\DateTimeImmutable,
     *   PreferredMaintenanceWindow?: null|string,
     *   PendingModifiedValues?: null|PendingModifiedValues|array,
     *   NotificationConfiguration?: null|NotificationConfiguration|array,
     *   CacheSecurityGroups?: null|CacheSecurityGroupMembership[],
     *   CacheParameterGroup?: null|CacheParameterGroupStatus|array,
     *   CacheSubnetGroupName?: null|string,
     *   CacheNodes?: null|CacheNode[],
     *   AutoMinorVersionUpgrade?: null|bool,
     *   SecurityGroups?: null|SecurityGroupMembership[],
     *   ReplicationGroupId?: null|string,
     *   SnapshotRetentionLimit?: null|int,
     *   SnapshotWindow?: null|string,
     *   AuthTokenEnabled?: null|bool,
     *   AuthTokenLastModifiedDate?: null|\DateTimeImmutable,
     *   TransitEncryptionEnabled?: null|bool,
     *   AtRestEncryptionEnabled?: null|bool,
     *   ARN?: null|string,
     *   ReplicationGroupLogDeliveryEnabled?: null|bool,
     *   LogDeliveryConfigurations?: null|LogDeliveryConfiguration[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cacheClusterId = $input['CacheClusterId'] ?? null;
        $this->configurationEndpoint = isset($input['ConfigurationEndpoint']) ? Endpoint::create($input['ConfigurationEndpoint']) : null;
        $this->clientDownloadLandingPage = $input['ClientDownloadLandingPage'] ?? null;
        $this->cacheNodeType = $input['CacheNodeType'] ?? null;
        $this->engine = $input['Engine'] ?? null;
        $this->engineVersion = $input['EngineVersion'] ?? null;
        $this->cacheClusterStatus = $input['CacheClusterStatus'] ?? null;
        $this->numCacheNodes = $input['NumCacheNodes'] ?? null;
        $this->preferredAvailabilityZone = $input['PreferredAvailabilityZone'] ?? null;
        $this->preferredOutpostArn = $input['PreferredOutpostArn'] ?? null;
        $this->cacheClusterCreateTime = $input['CacheClusterCreateTime'] ?? null;
        $this->preferredMaintenanceWindow = $input['PreferredMaintenanceWindow'] ?? null;
        $this->pendingModifiedValues = isset($input['PendingModifiedValues']) ? PendingModifiedValues::create($input['PendingModifiedValues']) : null;
        $this->notificationConfiguration = isset($input['NotificationConfiguration']) ? NotificationConfiguration::create($input['NotificationConfiguration']) : null;
        $this->cacheSecurityGroups = isset($input['CacheSecurityGroups']) ? array_map([CacheSecurityGroupMembership::class, 'create'], $input['CacheSecurityGroups']) : null;
        $this->cacheParameterGroup = isset($input['CacheParameterGroup']) ? CacheParameterGroupStatus::create($input['CacheParameterGroup']) : null;
        $this->cacheSubnetGroupName = $input['CacheSubnetGroupName'] ?? null;
        $this->cacheNodes = isset($input['CacheNodes']) ? array_map([CacheNode::class, 'create'], $input['CacheNodes']) : null;
        $this->autoMinorVersionUpgrade = $input['AutoMinorVersionUpgrade'] ?? null;
        $this->securityGroups = isset($input['SecurityGroups']) ? array_map([SecurityGroupMembership::class, 'create'], $input['SecurityGroups']) : null;
        $this->replicationGroupId = $input['ReplicationGroupId'] ?? null;
        $this->snapshotRetentionLimit = $input['SnapshotRetentionLimit'] ?? null;
        $this->snapshotWindow = $input['SnapshotWindow'] ?? null;
        $this->authTokenEnabled = $input['AuthTokenEnabled'] ?? null;
        $this->authTokenLastModifiedDate = $input['AuthTokenLastModifiedDate'] ?? null;
        $this->transitEncryptionEnabled = $input['TransitEncryptionEnabled'] ?? null;
        $this->atRestEncryptionEnabled = $input['AtRestEncryptionEnabled'] ?? null;
        $this->arn = $input['ARN'] ?? null;
        $this->replicationGroupLogDeliveryEnabled = $input['ReplicationGroupLogDeliveryEnabled'] ?? null;
        $this->logDeliveryConfigurations = isset($input['LogDeliveryConfigurations']) ? array_map([LogDeliveryConfiguration::class, 'create'], $input['LogDeliveryConfigurations']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getAtRestEncryptionEnabled(): ?bool
    {
        return $this->atRestEncryptionEnabled;
    }

    public function getAuthTokenEnabled(): ?bool
    {
        return $this->authTokenEnabled;
    }

    public function getAuthTokenLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->authTokenLastModifiedDate;
    }

    public function getAutoMinorVersionUpgrade(): ?bool
    {
        return $this->autoMinorVersionUpgrade;
    }

    public function getCacheClusterCreateTime(): ?\DateTimeImmutable
    {
        return $this->cacheClusterCreateTime;
    }

    public function getCacheClusterId(): ?string
    {
        return $this->cacheClusterId;
    }

    public function getCacheClusterStatus(): ?string
    {
        return $this->cacheClusterStatus;
    }

    public function getCacheNodeType(): ?string
    {
        return $this->cacheNodeType;
    }

    /**
     * @return CacheNode[]
     */
    public function getCacheNodes(): array
    {
        return $this->cacheNodes ?? [];
    }

    public function getCacheParameterGroup(): ?CacheParameterGroupStatus
    {
        return $this->cacheParameterGroup;
    }

    /**
     * @return CacheSecurityGroupMembership[]
     */
    public function getCacheSecurityGroups(): array
    {
        return $this->cacheSecurityGroups ?? [];
    }

    public function getCacheSubnetGroupName(): ?string
    {
        return $this->cacheSubnetGroupName;
    }

    public function getClientDownloadLandingPage(): ?string
    {
        return $this->clientDownloadLandingPage;
    }

    public function getConfigurationEndpoint(): ?Endpoint
    {
        return $this->configurationEndpoint;
    }

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function getEngineVersion(): ?string
    {
        return $this->engineVersion;
    }

    /**
     * @return LogDeliveryConfiguration[]
     */
    public function getLogDeliveryConfigurations(): array
    {
        return $this->logDeliveryConfigurations ?? [];
    }

    public function getNotificationConfiguration(): ?NotificationConfiguration
    {
        return $this->notificationConfiguration;
    }

    public function getNumCacheNodes(): ?int
    {
        return $this->numCacheNodes;
    }

    public function getPendingModifiedValues(): ?PendingModifiedValues
    {
        return $this->pendingModifiedValues;
    }

    public function getPreferredAvailabilityZone(): ?string
    {
        return $this->preferredAvailabilityZone;
    }

    public function getPreferredMaintenanceWindow(): ?string
    {
        return $this->preferredMaintenanceWindow;
    }

    public function getPreferredOutpostArn(): ?string
    {
        return $this->preferredOutpostArn;
    }

    public function getReplicationGroupId(): ?string
    {
        return $this->replicationGroupId;
    }

    public function getReplicationGroupLogDeliveryEnabled(): ?bool
    {
        return $this->replicationGroupLogDeliveryEnabled;
    }

    /**
     * @return SecurityGroupMembership[]
     */
    public function getSecurityGroups(): array
    {
        return $this->securityGroups ?? [];
    }

    public function getSnapshotRetentionLimit(): ?int
    {
        return $this->snapshotRetentionLimit;
    }

    public function getSnapshotWindow(): ?string
    {
        return $this->snapshotWindow;
    }

    public function getTransitEncryptionEnabled(): ?bool
    {
        return $this->transitEncryptionEnabled;
    }
}
