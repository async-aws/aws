<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\DataCatalogType;

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
     */
    private $name;

    /**
     * An optional description of the data catalog.
     */
    private $description;

    /**
     * The type of data catalog to create: `LAMBDA` for a federated catalog, `HIVE` for an external hive metastore, or
     * `GLUE` for an Glue Data Catalog.
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
     * -
     * - For the `LAMBDA` data catalog type, use one of the following sets of required parameters, but not both.
     *
     *   - If you have one Lambda function that processes metadata and another for reading the actual data, use the
     *     following syntax. Both parameters are required.
     *
     *     `metadata-function=*lambda_arn*, record-function=*lambda_arn*`
     *   -
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
     *   -
     *   - Queries that specify a Glue Data Catalog other than the default `AwsDataCatalog` must be run on Athena engine
     *     version 2.
     */
    private $parameters;

    /**
     * @param array{
     *   Name: string,
     *   Description?: null|string,
     *   Type: DataCatalogType::*,
     *   Parameters?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->type = $input['Type'] ?? null;
        $this->parameters = $input['Parameters'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->description;
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
     * @return DataCatalogType::*
     */
    public function getType(): string
    {
        return $this->type;
    }
}
