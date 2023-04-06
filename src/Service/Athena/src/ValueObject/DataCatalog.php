<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\DataCatalogType;

/**
 * The data catalog returned.
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
