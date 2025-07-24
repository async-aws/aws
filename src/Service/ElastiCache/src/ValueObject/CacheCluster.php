<?php

namespace AsyncAws\ElastiCache\ValueObject;

use AsyncAws\ElastiCache\Enum\IpDiscovery;
use AsyncAws\ElastiCache\Enum\NetworkType;
use AsyncAws\ElastiCache\Enum\TransitEncryptionMode;

/**
 * Contains all of the attributes of a specific cluster.
 */
final class CacheCluster
{
    /**
     * The user-supplied identifier of the cluster. This identifier is a unique key that identifies a cluster.
     *
     * @var string|null
     */
    private $cacheClusterId;

    /**
     * Represents a Memcached cluster endpoint which can be used by an application to connect to any node in the cluster.
     * The configuration endpoint will always have `.cfg` in it.
     *
     * Example: `mem-3.9dvc4r.cfg.usw2.cache.amazonaws.com:11211`
     *
     * @var Endpoint|null
     */
    private $configurationEndpoint;

    /**
     * The URL of the web page where you can download the latest ElastiCache client library.
     *
     * @var string|null
     */
    private $clientDownloadLandingPage;

    /**
     * The name of the compute and memory capacity node type for the cluster.
     *
     * The following node types are supported by ElastiCache. Generally speaking, the current generation types provide more
     * memory and computational power at lower cost when compared to their equivalent previous generation counterparts.
     *
     * - General purpose:
     *
     *   - Current generation:
     *
     *     **M7g node types**: `cache.m7g.large`, `cache.m7g.xlarge`, `cache.m7g.2xlarge`, `cache.m7g.4xlarge`,
     *     `cache.m7g.8xlarge`, `cache.m7g.12xlarge`, `cache.m7g.16xlarge`
     *
     *     > For region availability, see Supported Node Types [^1]
     *
     *     **M6g node types** (available only for Redis OSS engine version 5.0.6 onward and for Memcached engine version
     *     1.5.16 onward): `cache.m6g.large`, `cache.m6g.xlarge`, `cache.m6g.2xlarge`, `cache.m6g.4xlarge`,
     *     `cache.m6g.8xlarge`, `cache.m6g.12xlarge`, `cache.m6g.16xlarge`
     *
     *     **M5 node types:**`cache.m5.large`, `cache.m5.xlarge`, `cache.m5.2xlarge`, `cache.m5.4xlarge`,
     *     `cache.m5.12xlarge`, `cache.m5.24xlarge`
     *
     *     **M4 node types:**`cache.m4.large`, `cache.m4.xlarge`, `cache.m4.2xlarge`, `cache.m4.4xlarge`,
     *     `cache.m4.10xlarge`
     *
     *     **T4g node types** (available only for Redis OSS engine version 5.0.6 onward and Memcached engine version 1.5.16
     *     onward): `cache.t4g.micro`, `cache.t4g.small`, `cache.t4g.medium`
     *
     *     **T3 node types:**`cache.t3.micro`, `cache.t3.small`, `cache.t3.medium`
     *
     *     **T2 node types:**`cache.t2.micro`, `cache.t2.small`, `cache.t2.medium`
     *   - Previous generation: (not recommended. Existing clusters are still supported but creation of new clusters is not
     *     supported for these types.)
     *
     *     **T1 node types:**`cache.t1.micro`
     *
     *     **M1 node types:**`cache.m1.small`, `cache.m1.medium`, `cache.m1.large`, `cache.m1.xlarge`
     *
     *     **M3 node types:**`cache.m3.medium`, `cache.m3.large`, `cache.m3.xlarge`, `cache.m3.2xlarge`
     *
     * - Compute optimized:
     *
     *   - Previous generation: (not recommended. Existing clusters are still supported but creation of new clusters is not
     *     supported for these types.)
     *
     *     **C1 node types:**`cache.c1.xlarge`
     *
     * - Memory optimized:
     *
     *   - Current generation:
     *
     *     **R7g node types**: `cache.r7g.large`, `cache.r7g.xlarge`, `cache.r7g.2xlarge`, `cache.r7g.4xlarge`,
     *     `cache.r7g.8xlarge`, `cache.r7g.12xlarge`, `cache.r7g.16xlarge`
     *
     *     > For region availability, see Supported Node Types [^2]
     *
     *     **R6g node types** (available only for Redis OSS engine version 5.0.6 onward and for Memcached engine version
     *     1.5.16 onward): `cache.r6g.large`, `cache.r6g.xlarge`, `cache.r6g.2xlarge`, `cache.r6g.4xlarge`,
     *     `cache.r6g.8xlarge`, `cache.r6g.12xlarge`, `cache.r6g.16xlarge`
     *
     *     **R5 node types:**`cache.r5.large`, `cache.r5.xlarge`, `cache.r5.2xlarge`, `cache.r5.4xlarge`,
     *     `cache.r5.12xlarge`, `cache.r5.24xlarge`
     *
     *     **R4 node types:**`cache.r4.large`, `cache.r4.xlarge`, `cache.r4.2xlarge`, `cache.r4.4xlarge`,
     *     `cache.r4.8xlarge`, `cache.r4.16xlarge`
     *   - Previous generation: (not recommended. Existing clusters are still supported but creation of new clusters is not
     *     supported for these types.)
     *
     *     **M2 node types:**`cache.m2.xlarge`, `cache.m2.2xlarge`, `cache.m2.4xlarge`
     *
     *     **R3 node types:**`cache.r3.large`, `cache.r3.xlarge`, `cache.r3.2xlarge`, `cache.r3.4xlarge`, `cache.r3.8xlarge`
     *
     *
     * **Additional node type info**
     *
     * - All current generation instance types are created in Amazon VPC by default.
     * - Valkey or Redis OSS append-only files (AOF) are not supported for T1 or T2 instances.
     * - Valkey or Redis OSS Multi-AZ with automatic failover is not supported on T1 instances.
     * - The configuration variables `appendonly` and `appendfsync` are not supported on Valkey, or on Redis OSS version
     *   2.8.22 and later.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonElastiCache/latest/dg/CacheNodes.SupportedTypes.html#CacheNodes.SupportedTypesByRegion
     * [^2]: https://docs.aws.amazon.com/AmazonElastiCache/latest/dg/CacheNodes.SupportedTypes.html#CacheNodes.SupportedTypesByRegion
     *
     * @var string|null
     */
    private $cacheNodeType;

    /**
     * The name of the cache engine (`memcached` or `redis`) to be used for this cluster.
     *
     * @var string|null
     */
    private $engine;

    /**
     * The version of the cache engine that is used in this cluster.
     *
     * @var string|null
     */
    private $engineVersion;

    /**
     * The current state of this cluster, one of the following values: `available`, `creating`, `deleted`, `deleting`,
     * `incompatible-network`, `modifying`, `rebooting cluster nodes`, `restore-failed`, or `snapshotting`.
     *
     * @var string|null
     */
    private $cacheClusterStatus;

    /**
     * The number of cache nodes in the cluster.
     *
     * For clusters running Valkey or Redis OSS, this value must be 1. For clusters running Memcached, this value must be
     * between 1 and 40.
     *
     * @var int|null
     */
    private $numCacheNodes;

    /**
     * The name of the Availability Zone in which the cluster is located or "Multiple" if the cache nodes are located in
     * different Availability Zones.
     *
     * @var string|null
     */
    private $preferredAvailabilityZone;

    /**
     * The outpost ARN in which the cache cluster is created.
     *
     * @var string|null
     */
    private $preferredOutpostArn;

    /**
     * The date and time when the cluster was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $cacheClusterCreateTime;

    /**
     * Specifies the weekly time range during which maintenance on the cluster is performed. It is specified as a range in
     * the format ddd:hh24:mi-ddd:hh24:mi (24H Clock UTC). The minimum maintenance window is a 60 minute period.
     *
     * Valid values for `ddd` are:
     *
     * - `sun`
     * - `mon`
     * - `tue`
     * - `wed`
     * - `thu`
     * - `fri`
     * - `sat`
     *
     * Example: `sun:23:00-mon:01:30`
     *
     * @var string|null
     */
    private $preferredMaintenanceWindow;

    /**
     * @var PendingModifiedValues|null
     */
    private $pendingModifiedValues;

    /**
     * Describes a notification topic and its status. Notification topics are used for publishing ElastiCache events to
     * subscribers using Amazon Simple Notification Service (SNS).
     *
     * @var NotificationConfiguration|null
     */
    private $notificationConfiguration;

    /**
     * A list of cache security group elements, composed of name and status sub-elements.
     *
     * @var CacheSecurityGroupMembership[]|null
     */
    private $cacheSecurityGroups;

    /**
     * Status of the cache parameter group.
     *
     * @var CacheParameterGroupStatus|null
     */
    private $cacheParameterGroup;

    /**
     * The name of the cache subnet group associated with the cluster.
     *
     * @var string|null
     */
    private $cacheSubnetGroupName;

    /**
     * A list of cache nodes that are members of the cluster.
     *
     * @var CacheNode[]|null
     */
    private $cacheNodes;

    /**
     *  If you are running Valkey or Redis OSS engine version 6.0 or later, set this parameter to yes if you want to opt-in
     * to the next auto minor version upgrade campaign. This parameter is disabled for previous versions. .
     *
     * @var bool|null
     */
    private $autoMinorVersionUpgrade;

    /**
     * A list of VPC Security Groups associated with the cluster.
     *
     * @var SecurityGroupMembership[]|null
     */
    private $securityGroups;

    /**
     * The replication group to which this cluster belongs. If this field is empty, the cluster is not associated with any
     * replication group.
     *
     * @var string|null
     */
    private $replicationGroupId;

    /**
     * The number of days for which ElastiCache retains automatic cluster snapshots before deleting them. For example, if
     * you set `SnapshotRetentionLimit` to 5, a snapshot that was taken today is retained for 5 days before being deleted.
     *
     * ! If the value of SnapshotRetentionLimit is set to zero (0), backups are turned off.
     *
     * @var int|null
     */
    private $snapshotRetentionLimit;

    /**
     * The daily time range (in UTC) during which ElastiCache begins taking a daily snapshot of your cluster.
     *
     * Example: `05:00-09:00`
     *
     * @var string|null
     */
    private $snapshotWindow;

    /**
     * A flag that enables using an `AuthToken` (password) when issuing Valkey or Redis OSS commands.
     *
     * Default: `false`
     *
     * @var bool|null
     */
    private $authTokenEnabled;

    /**
     * The date the auth token was last modified.
     *
     * @var \DateTimeImmutable|null
     */
    private $authTokenLastModifiedDate;

    /**
     * A flag that enables in-transit encryption when set to `true`.
     *
     * **Required:** Only available when creating a replication group in an Amazon VPC using Redis OSS version `3.2.6`,
     * `4.x` or later.
     *
     * Default: `false`
     *
     * @var bool|null
     */
    private $transitEncryptionEnabled;

    /**
     * A flag that enables encryption at-rest when set to `true`.
     *
     * You cannot modify the value of `AtRestEncryptionEnabled` after the cluster is created. To enable at-rest encryption
     * on a cluster you must set `AtRestEncryptionEnabled` to `true` when you create a cluster.
     *
     * **Required:** Only available when creating a replication group in an Amazon VPC using Redis OSS version `3.2.6`,
     * `4.x` or later.
     *
     * Default: `false`
     *
     * @var bool|null
     */
    private $atRestEncryptionEnabled;

    /**
     * The ARN (Amazon Resource Name) of the cache cluster.
     *
     * @var string|null
     */
    private $arn;

    /**
     * A boolean value indicating whether log delivery is enabled for the replication group.
     *
     * @var bool|null
     */
    private $replicationGroupLogDeliveryEnabled;

    /**
     * Returns the destination, format and type of the logs.
     *
     * @var LogDeliveryConfiguration[]|null
     */
    private $logDeliveryConfigurations;

    /**
     * Must be either `ipv4` | `ipv6` | `dual_stack`. IPv6 is supported for workloads using Valkey 7.2 and above, Redis OSS
     * engine version 6.2 7.1 or Memcached engine version 1.6.6 and above on all instances built on the Nitro system [^1].
     *
     * [^1]: http://aws.amazon.com/ec2/nitro/
     *
     * @var NetworkType::*|string|null
     */
    private $networkType;

    /**
     * The network type associated with the cluster, either `ipv4` | `ipv6`. IPv6 is supported for workloads using Valkey
     * 7.2 and above, Redis OSS engine version 6.2 to 7.1 or Memcached engine version 1.6.6 and above on all instances built
     * on the Nitro system [^1].
     *
     * [^1]: http://aws.amazon.com/ec2/nitro/
     *
     * @var IpDiscovery::*|string|null
     */
    private $ipDiscovery;

    /**
     * A setting that allows you to migrate your clients to use in-transit encryption, with no downtime.
     *
     * @var TransitEncryptionMode::*|string|null
     */
    private $transitEncryptionMode;

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
     *   CacheSecurityGroups?: null|array<CacheSecurityGroupMembership|array>,
     *   CacheParameterGroup?: null|CacheParameterGroupStatus|array,
     *   CacheSubnetGroupName?: null|string,
     *   CacheNodes?: null|array<CacheNode|array>,
     *   AutoMinorVersionUpgrade?: null|bool,
     *   SecurityGroups?: null|array<SecurityGroupMembership|array>,
     *   ReplicationGroupId?: null|string,
     *   SnapshotRetentionLimit?: null|int,
     *   SnapshotWindow?: null|string,
     *   AuthTokenEnabled?: null|bool,
     *   AuthTokenLastModifiedDate?: null|\DateTimeImmutable,
     *   TransitEncryptionEnabled?: null|bool,
     *   AtRestEncryptionEnabled?: null|bool,
     *   ARN?: null|string,
     *   ReplicationGroupLogDeliveryEnabled?: null|bool,
     *   LogDeliveryConfigurations?: null|array<LogDeliveryConfiguration|array>,
     *   NetworkType?: null|NetworkType::*|string,
     *   IpDiscovery?: null|IpDiscovery::*|string,
     *   TransitEncryptionMode?: null|TransitEncryptionMode::*|string,
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
        $this->networkType = $input['NetworkType'] ?? null;
        $this->ipDiscovery = $input['IpDiscovery'] ?? null;
        $this->transitEncryptionMode = $input['TransitEncryptionMode'] ?? null;
    }

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
     *   CacheSecurityGroups?: null|array<CacheSecurityGroupMembership|array>,
     *   CacheParameterGroup?: null|CacheParameterGroupStatus|array,
     *   CacheSubnetGroupName?: null|string,
     *   CacheNodes?: null|array<CacheNode|array>,
     *   AutoMinorVersionUpgrade?: null|bool,
     *   SecurityGroups?: null|array<SecurityGroupMembership|array>,
     *   ReplicationGroupId?: null|string,
     *   SnapshotRetentionLimit?: null|int,
     *   SnapshotWindow?: null|string,
     *   AuthTokenEnabled?: null|bool,
     *   AuthTokenLastModifiedDate?: null|\DateTimeImmutable,
     *   TransitEncryptionEnabled?: null|bool,
     *   AtRestEncryptionEnabled?: null|bool,
     *   ARN?: null|string,
     *   ReplicationGroupLogDeliveryEnabled?: null|bool,
     *   LogDeliveryConfigurations?: null|array<LogDeliveryConfiguration|array>,
     *   NetworkType?: null|NetworkType::*|string,
     *   IpDiscovery?: null|IpDiscovery::*|string,
     *   TransitEncryptionMode?: null|TransitEncryptionMode::*|string,
     * }|CacheCluster $input
     */
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
     * @return IpDiscovery::*|string|null
     */
    public function getIpDiscovery(): ?string
    {
        return $this->ipDiscovery;
    }

    /**
     * @return LogDeliveryConfiguration[]
     */
    public function getLogDeliveryConfigurations(): array
    {
        return $this->logDeliveryConfigurations ?? [];
    }

    /**
     * @return NetworkType::*|string|null
     */
    public function getNetworkType(): ?string
    {
        return $this->networkType;
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

    /**
     * @return TransitEncryptionMode::*|string|null
     */
    public function getTransitEncryptionMode(): ?string
    {
        return $this->transitEncryptionMode;
    }
}
