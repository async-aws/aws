<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\ConnectionType;
use AsyncAws\Athena\Enum\DataCatalogStatus;
use AsyncAws\Athena\Enum\DataCatalogType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about a data catalog in an Amazon Web Services account.
 *
 * > In the Athena console, data catalogs are listed as "data sources" on the **Data sources** page under the **Data
 * > source name** column.
 */
final class DataCatalog
{
    /**
     * The name of the data catalog. The catalog name must be unique for the Amazon Web Services account and can use a
     * maximum of 127 alphanumeric, underscore, at sign, or hyphen characters. The remainder of the length constraint of 256
     * is reserved for use by Athena.
     *
     * @var string
     */
    private $name;

    /**
     * An optional description of the data catalog.
     *
     * @var string|null
     */
    private $description;

    /**
     * The type of data catalog to create: `LAMBDA` for a federated catalog, `GLUE` for an Glue Data Catalog, and `HIVE` for
     * an external Apache Hive metastore. `FEDERATED` is a federated catalog for which Athena creates the connection and the
     * Lambda function for you based on the parameters that you pass.
     *
     * @var DataCatalogType::*
     */
    private $type;

    /**
     * Specifies the Lambda function or functions to use for the data catalog. This is a mapping whose values depend on the
     * catalog type.
     *
     * - For the `HIVE` data catalog type, use the following syntax. The `metadata-function` parameter is required. `The
     *   sdk-version` parameter is optional and defaults to the currently supported version.
     *
     *   `metadata-function=*lambda_arn*, sdk-version=*version_number*`
     * - For the `LAMBDA` data catalog type, use one of the following sets of required parameters, but not both.
     *
     *   - If you have one Lambda function that processes metadata and another for reading the actual data, use the
     *     following syntax. Both parameters are required.
     *
     *     `metadata-function=*lambda_arn*, record-function=*lambda_arn*`
     *   - If you have a composite Lambda function that processes both metadata and data, use the following syntax to
     *     specify your Lambda function.
     *
     *     `function=*lambda_arn*`
     *
     * - The `GLUE` type takes a catalog ID parameter and is required. The `*catalog_id*` is the account ID of the Amazon
     *   Web Services account to which the Glue catalog belongs.
     *
     *   `catalog-id=*catalog_id*`
     *
     *   - The `GLUE` data catalog type also applies to the default `AwsDataCatalog` that already exists in your account, of
     *     which you can have only one and cannot modify.
     *
     * - The `FEDERATED` data catalog type uses one of the following parameters, but not both. Use `connection-arn` for an
     *   existing Glue connection. Use `connection-type` and `connection-properties` to specify the configuration setting
     *   for a new connection.
     *
     *   - `connection-arn:*<glue_connection_arn_to_reuse>*`
     *   - `connection-type:MYSQL|REDSHIFT|...., connection-properties:"*<json_string>*"`
     *
     *     For *`<json_string>`*, use escaped JSON text, as in the following example.
     *
     *     `"{\"spill_bucket\":\"my_spill\",\"spill_prefix\":\"athena-spill\",\"host\":\"abc12345.snowflakecomputing.com\",\"port\":\"1234\",\"warehouse\":\"DEV_WH\",\"database\":\"TEST\",\"schema\":\"PUBLIC\",\"SecretArn\":\"arn:aws:secretsmanager:ap-south-1:111122223333:secret:snowflake-XHb67j\"}"`
     *
     * @var array<string, string>|null
     */
    private $parameters;

    /**
     * The status of the creation or deletion of the data catalog.
     *
     * - The `LAMBDA`, `GLUE`, and `HIVE` data catalog types are created synchronously. Their status is either
     *   `CREATE_COMPLETE` or `CREATE_FAILED`.
     * - The `FEDERATED` data catalog type is created asynchronously.
     *
     * Data catalog creation status:
     *
     * - `CREATE_IN_PROGRESS`: Federated data catalog creation in progress.
     * - `CREATE_COMPLETE`: Data catalog creation complete.
     * - `CREATE_FAILED`: Data catalog could not be created.
     * - `CREATE_FAILED_CLEANUP_IN_PROGRESS`: Federated data catalog creation failed and is being removed.
     * - `CREATE_FAILED_CLEANUP_COMPLETE`: Federated data catalog creation failed and was removed.
     * - `CREATE_FAILED_CLEANUP_FAILED`: Federated data catalog creation failed but could not be removed.
     *
     * Data catalog deletion status:
     *
     * - `DELETE_IN_PROGRESS`: Federated data catalog deletion in progress.
     * - `DELETE_COMPLETE`: Federated data catalog deleted.
     * - `DELETE_FAILED`: Federated data catalog could not be deleted.
     *
     * @var DataCatalogStatus::*|null
     */
    private $status;

    /**
     * The type of connection for a `FEDERATED` data catalog (for example, `REDSHIFT`, `MYSQL`, or `SQLSERVER`). For
     * information about individual connectors, see Available data source connectors [^1].
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/connectors-available.html
     *
     * @var ConnectionType::*|null
     */
    private $connectionType;

    /**
     * Text of the error that occurred during data catalog creation or deletion.
     *
     * @var string|null
     */
    private $error;

    /**
     * @param array{
     *   Name: string,
     *   Description?: string|null,
     *   Type: DataCatalogType::*,
     *   Parameters?: array<string, string>|null,
     *   Status?: DataCatalogStatus::*|null,
     *   ConnectionType?: ConnectionType::*|null,
     *   Error?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->description = $input['Description'] ?? null;
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
        $this->parameters = $input['Parameters'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->connectionType = $input['ConnectionType'] ?? null;
        $this->error = $input['Error'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Description?: string|null,
     *   Type: DataCatalogType::*,
     *   Parameters?: array<string, string>|null,
     *   Status?: DataCatalogStatus::*|null,
     *   ConnectionType?: ConnectionType::*|null,
     *   Error?: string|null,
     * }|DataCatalog $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ConnectionType::*|null
     */
    public function getConnectionType(): ?string
    {
        return $this->connectionType;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @return DataCatalogStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return DataCatalogType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
