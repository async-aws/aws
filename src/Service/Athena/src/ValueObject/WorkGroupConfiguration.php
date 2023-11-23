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
     * no output location is provided. For more information, see Working with query results, recent queries, and output
     * files [^1].
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/querying.html
     *
     * @var ResultConfiguration|null
     */
    private $resultConfiguration;

    /**
     * If set to "true", the settings for the workgroup override client-side settings. If set to "false", client-side
     * settings are used. For more information, see Workgroup Settings Override Client-Side Settings [^1].
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
     * Role used in a Spark session for accessing the user's resources. This property applies only to Spark-enabled
     * workgroups.
     *
     * @var string|null
     */
    private $executionRole;

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
     * @param array{
     *   ResultConfiguration?: null|ResultConfiguration|array,
     *   EnforceWorkGroupConfiguration?: null|bool,
     *   PublishCloudWatchMetricsEnabled?: null|bool,
     *   BytesScannedCutoffPerQuery?: null|int,
     *   RequesterPaysEnabled?: null|bool,
     *   EngineVersion?: null|EngineVersion|array,
     *   AdditionalConfiguration?: null|string,
     *   ExecutionRole?: null|string,
     *   CustomerContentEncryptionConfiguration?: null|CustomerContentEncryptionConfiguration|array,
     *   EnableMinimumEncryptionConfiguration?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->resultConfiguration = isset($input['ResultConfiguration']) ? ResultConfiguration::create($input['ResultConfiguration']) : null;
        $this->enforceWorkGroupConfiguration = $input['EnforceWorkGroupConfiguration'] ?? null;
        $this->publishCloudWatchMetricsEnabled = $input['PublishCloudWatchMetricsEnabled'] ?? null;
        $this->bytesScannedCutoffPerQuery = $input['BytesScannedCutoffPerQuery'] ?? null;
        $this->requesterPaysEnabled = $input['RequesterPaysEnabled'] ?? null;
        $this->engineVersion = isset($input['EngineVersion']) ? EngineVersion::create($input['EngineVersion']) : null;
        $this->additionalConfiguration = $input['AdditionalConfiguration'] ?? null;
        $this->executionRole = $input['ExecutionRole'] ?? null;
        $this->customerContentEncryptionConfiguration = isset($input['CustomerContentEncryptionConfiguration']) ? CustomerContentEncryptionConfiguration::create($input['CustomerContentEncryptionConfiguration']) : null;
        $this->enableMinimumEncryptionConfiguration = $input['EnableMinimumEncryptionConfiguration'] ?? null;
    }

    /**
     * @param array{
     *   ResultConfiguration?: null|ResultConfiguration|array,
     *   EnforceWorkGroupConfiguration?: null|bool,
     *   PublishCloudWatchMetricsEnabled?: null|bool,
     *   BytesScannedCutoffPerQuery?: null|int,
     *   RequesterPaysEnabled?: null|bool,
     *   EngineVersion?: null|EngineVersion|array,
     *   AdditionalConfiguration?: null|string,
     *   ExecutionRole?: null|string,
     *   CustomerContentEncryptionConfiguration?: null|CustomerContentEncryptionConfiguration|array,
     *   EnableMinimumEncryptionConfiguration?: null|bool,
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

    public function getEngineVersion(): ?EngineVersion
    {
        return $this->engineVersion;
    }

    public function getExecutionRole(): ?string
    {
        return $this->executionRole;
    }

    public function getPublishCloudWatchMetricsEnabled(): ?bool
    {
        return $this->publishCloudWatchMetricsEnabled;
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
