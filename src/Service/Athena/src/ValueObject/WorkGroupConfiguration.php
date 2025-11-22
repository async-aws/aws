<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The configuration of the workgroup, which includes the location in Amazon S3 where query and calculation results are
 * stored, the encryption option, if any, used for query and calculation results, whether the Amazon CloudWatch Metrics
 * are enabled for the workgroup and whether workgroup settings override query settings, and the data usage limits for
 * the amount of data scanned per query or per workgroup. The workgroup settings override is specified in
 * `EnforceWorkGroupConfiguration` (true/false) in the `WorkGroupConfiguration`. See
 * WorkGroupConfiguration$EnforceWorkGroupConfiguration.
 */
final class WorkGroupConfiguration
{
    /**
     * The configuration for the workgroup, which includes the location in Amazon S3 where query and calculation results are
     * stored and the encryption option, if any, used for query and calculation results. To run the query, you must specify
     * the query results location using one of the ways: either in the workgroup using this setting, or for individual
     * queries (client-side), using ResultConfiguration$OutputLocation. If none of them is set, Athena issues an error that
     * no output location is provided.
     *
     * @var ResultConfiguration|null
     */
    private $resultConfiguration;

    /**
     * The configuration for storing results in Athena owned storage, which includes whether this feature is enabled;
     * whether encryption configuration, if any, is used for encrypting query results.
     *
     * @var ManagedQueryResultsConfiguration|null
     */
    private $managedQueryResultsConfiguration;

    /**
     * If set to "true", the settings for the workgroup override client-side settings. If set to "false", client-side
     * settings are used. This property is not required for Apache Spark enabled workgroups. For more information, see
     * Workgroup Settings Override Client-Side Settings [^1].
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
     *
     * @var bool|null
     */
    private $enforceWorkGroupConfiguration;

    /**
     * Indicates that the Amazon CloudWatch metrics are enabled for the workgroup.
     *
     * @var bool|null
     */
    private $publishCloudWatchMetricsEnabled;

    /**
     * The upper data usage limit (cutoff) for the amount of bytes a single query in a workgroup is allowed to scan.
     *
     * @var int|null
     */
    private $bytesScannedCutoffPerQuery;

    /**
     * If set to `true`, allows members assigned to a workgroup to reference Amazon S3 Requester Pays buckets in queries. If
     * set to `false`, workgroup members cannot query data from Requester Pays buckets, and queries that retrieve data from
     * Requester Pays buckets cause an error. The default is `false`. For more information about Requester Pays buckets, see
     * Requester Pays Buckets [^1] in the *Amazon Simple Storage Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/RequesterPaysBuckets.html
     *
     * @var bool|null
     */
    private $requesterPaysEnabled;

    /**
     * The engine version that all queries running on the workgroup use. Queries on the `AmazonAthenaPreviewFunctionality`
     * workgroup run on the preview engine regardless of this setting.
     *
     * @var EngineVersion|null
     */
    private $engineVersion;

    /**
     * Specifies a user defined JSON string that is passed to the notebook engine.
     *
     * @var string|null
     */
    private $additionalConfiguration;

    /**
     * The ARN of the execution role used to access user resources for Spark sessions and IAM Identity Center enabled
     * workgroups. This property applies only to Spark enabled workgroups and IAM Identity Center enabled workgroups. The
     * property is required for IAM Identity Center enabled workgroups.
     *
     * @var string|null
     */
    private $executionRole;

    /**
     * Contains the configuration settings for managed log persistence, delivering logs to Amazon S3 buckets, Amazon
     * CloudWatch log groups etc.
     *
     * @var MonitoringConfiguration|null
     */
    private $monitoringConfiguration;

    /**
     * @var EngineConfiguration|null
     */
    private $engineConfiguration;

    /**
     * Specifies the KMS key that is used to encrypt the user's data stores in Athena. This setting does not apply to Athena
     * SQL workgroups.
     *
     * @var CustomerContentEncryptionConfiguration|null
     */
    private $customerContentEncryptionConfiguration;

    /**
     * Enforces a minimal level of encryption for the workgroup for query and calculation results that are written to Amazon
     * S3. When enabled, workgroup users can set encryption only to the minimum level set by the administrator or higher
     * when they submit queries.
     *
     * The `EnforceWorkGroupConfiguration` setting takes precedence over the `EnableMinimumEncryptionConfiguration` flag.
     * This means that if `EnforceWorkGroupConfiguration` is true, the `EnableMinimumEncryptionConfiguration` flag is
     * ignored, and the workgroup configuration for encryption is used.
     *
     * @var bool|null
     */
    private $enableMinimumEncryptionConfiguration;

    /**
     * Specifies whether the workgroup is IAM Identity Center supported.
     *
     * @var IdentityCenterConfiguration|null
     */
    private $identityCenterConfiguration;

    /**
     * Specifies whether Amazon S3 access grants are enabled for query results.
     *
     * @var QueryResultsS3AccessGrantsConfiguration|null
     */
    private $queryResultsS3AccessGrantsConfiguration;

    /**
     * @param array{
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   ManagedQueryResultsConfiguration?: ManagedQueryResultsConfiguration|array|null,
     *   EnforceWorkGroupConfiguration?: bool|null,
     *   PublishCloudWatchMetricsEnabled?: bool|null,
     *   BytesScannedCutoffPerQuery?: int|null,
     *   RequesterPaysEnabled?: bool|null,
     *   EngineVersion?: EngineVersion|array|null,
     *   AdditionalConfiguration?: string|null,
     *   ExecutionRole?: string|null,
     *   MonitoringConfiguration?: MonitoringConfiguration|array|null,
     *   EngineConfiguration?: EngineConfiguration|array|null,
     *   CustomerContentEncryptionConfiguration?: CustomerContentEncryptionConfiguration|array|null,
     *   EnableMinimumEncryptionConfiguration?: bool|null,
     *   IdentityCenterConfiguration?: IdentityCenterConfiguration|array|null,
     *   QueryResultsS3AccessGrantsConfiguration?: QueryResultsS3AccessGrantsConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->resultConfiguration = isset($input['ResultConfiguration']) ? ResultConfiguration::create($input['ResultConfiguration']) : null;
        $this->managedQueryResultsConfiguration = isset($input['ManagedQueryResultsConfiguration']) ? ManagedQueryResultsConfiguration::create($input['ManagedQueryResultsConfiguration']) : null;
        $this->enforceWorkGroupConfiguration = $input['EnforceWorkGroupConfiguration'] ?? null;
        $this->publishCloudWatchMetricsEnabled = $input['PublishCloudWatchMetricsEnabled'] ?? null;
        $this->bytesScannedCutoffPerQuery = $input['BytesScannedCutoffPerQuery'] ?? null;
        $this->requesterPaysEnabled = $input['RequesterPaysEnabled'] ?? null;
        $this->engineVersion = isset($input['EngineVersion']) ? EngineVersion::create($input['EngineVersion']) : null;
        $this->additionalConfiguration = $input['AdditionalConfiguration'] ?? null;
        $this->executionRole = $input['ExecutionRole'] ?? null;
        $this->monitoringConfiguration = isset($input['MonitoringConfiguration']) ? MonitoringConfiguration::create($input['MonitoringConfiguration']) : null;
        $this->engineConfiguration = isset($input['EngineConfiguration']) ? EngineConfiguration::create($input['EngineConfiguration']) : null;
        $this->customerContentEncryptionConfiguration = isset($input['CustomerContentEncryptionConfiguration']) ? CustomerContentEncryptionConfiguration::create($input['CustomerContentEncryptionConfiguration']) : null;
        $this->enableMinimumEncryptionConfiguration = $input['EnableMinimumEncryptionConfiguration'] ?? null;
        $this->identityCenterConfiguration = isset($input['IdentityCenterConfiguration']) ? IdentityCenterConfiguration::create($input['IdentityCenterConfiguration']) : null;
        $this->queryResultsS3AccessGrantsConfiguration = isset($input['QueryResultsS3AccessGrantsConfiguration']) ? QueryResultsS3AccessGrantsConfiguration::create($input['QueryResultsS3AccessGrantsConfiguration']) : null;
    }

    /**
     * @param array{
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   ManagedQueryResultsConfiguration?: ManagedQueryResultsConfiguration|array|null,
     *   EnforceWorkGroupConfiguration?: bool|null,
     *   PublishCloudWatchMetricsEnabled?: bool|null,
     *   BytesScannedCutoffPerQuery?: int|null,
     *   RequesterPaysEnabled?: bool|null,
     *   EngineVersion?: EngineVersion|array|null,
     *   AdditionalConfiguration?: string|null,
     *   ExecutionRole?: string|null,
     *   MonitoringConfiguration?: MonitoringConfiguration|array|null,
     *   EngineConfiguration?: EngineConfiguration|array|null,
     *   CustomerContentEncryptionConfiguration?: CustomerContentEncryptionConfiguration|array|null,
     *   EnableMinimumEncryptionConfiguration?: bool|null,
     *   IdentityCenterConfiguration?: IdentityCenterConfiguration|array|null,
     *   QueryResultsS3AccessGrantsConfiguration?: QueryResultsS3AccessGrantsConfiguration|array|null,
     * }|WorkGroupConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdditionalConfiguration(): ?string
    {
        return $this->additionalConfiguration;
    }

    public function getBytesScannedCutoffPerQuery(): ?int
    {
        return $this->bytesScannedCutoffPerQuery;
    }

    public function getCustomerContentEncryptionConfiguration(): ?CustomerContentEncryptionConfiguration
    {
        return $this->customerContentEncryptionConfiguration;
    }

    public function getEnableMinimumEncryptionConfiguration(): ?bool
    {
        return $this->enableMinimumEncryptionConfiguration;
    }

    public function getEnforceWorkGroupConfiguration(): ?bool
    {
        return $this->enforceWorkGroupConfiguration;
    }

    public function getEngineConfiguration(): ?EngineConfiguration
    {
        return $this->engineConfiguration;
    }

    public function getEngineVersion(): ?EngineVersion
    {
        return $this->engineVersion;
    }

    public function getExecutionRole(): ?string
    {
        return $this->executionRole;
    }

    public function getIdentityCenterConfiguration(): ?IdentityCenterConfiguration
    {
        return $this->identityCenterConfiguration;
    }

    public function getManagedQueryResultsConfiguration(): ?ManagedQueryResultsConfiguration
    {
        return $this->managedQueryResultsConfiguration;
    }

    public function getMonitoringConfiguration(): ?MonitoringConfiguration
    {
        return $this->monitoringConfiguration;
    }

    public function getPublishCloudWatchMetricsEnabled(): ?bool
    {
        return $this->publishCloudWatchMetricsEnabled;
    }

    public function getQueryResultsS3AccessGrantsConfiguration(): ?QueryResultsS3AccessGrantsConfiguration
    {
        return $this->queryResultsS3AccessGrantsConfiguration;
    }

    public function getRequesterPaysEnabled(): ?bool
    {
        return $this->requesterPaysEnabled;
    }

    public function getResultConfiguration(): ?ResultConfiguration
    {
        return $this->resultConfiguration;
    }
}
