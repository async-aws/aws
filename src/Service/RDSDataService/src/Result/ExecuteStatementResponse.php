<?php

namespace AsyncAws\RDSDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RDSDataService\ValueObject\ColumnMetadata;

class ExecuteStatementResponse extends Result
{
    /**
     * Metadata for the columns included in the results.
     */
    private $columnMetadata = [];

    /**
     * Values for fields generated during the request.
     */
    private $generatedFields;

    /**
     * The number of records updated by the request.
     */
    private $numberOfRecordsUpdated;

    /**
     * The records returned by the SQL statement.
     */
    private $records = [];

    /**
     * @return ColumnMetadata[]
     */
    public function getColumnMetadata(): array
    {
        $this->initialize();

        return $this->columnMetadata;
    }

    public function getGeneratedFields(): ?array
    {
        $this->initialize();

        return $this->generatedFields;
    }

    public function getNumberOfRecordsUpdated(): ?string
    {
        $this->initialize();

        return $this->numberOfRecordsUpdated;
    }

    /**
     * @return array[]
     */
    public function getRecords(): array
    {
        $this->initialize();

        return $this->records;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->columnMetadata = empty($data['columnMetadata']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new ColumnMetadata([
                    'arrayBaseColumnType' => isset($item['arrayBaseColumnType']) ? (int) $item['arrayBaseColumnType'] : null,
                    'isAutoIncrement' => isset($item['isAutoIncrement']) ? filter_var($item['isAutoIncrement'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'isCaseSensitive' => isset($item['isCaseSensitive']) ? filter_var($item['isCaseSensitive'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'isCurrency' => isset($item['isCurrency']) ? filter_var($item['isCurrency'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'isSigned' => isset($item['isSigned']) ? filter_var($item['isSigned'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'label' => isset($item['label']) ? (string) $item['label'] : null,
                    'name' => isset($item['name']) ? (string) $item['name'] : null,
                    'nullable' => isset($item['nullable']) ? (int) $item['nullable'] : null,
                    'precision' => isset($item['precision']) ? (int) $item['precision'] : null,
                    'scale' => isset($item['scale']) ? (int) $item['scale'] : null,
                    'schemaName' => isset($item['schemaName']) ? (string) $item['schemaName'] : null,
                    'tableName' => isset($item['tableName']) ? (string) $item['tableName'] : null,
                    'type' => isset($item['type']) ? (int) $item['type'] : null,
                    'typeName' => isset($item['typeName']) ? (string) $item['typeName'] : null,
                ]);
            }

            return $items;
        })($data['columnMetadata']);
        $this->generatedFields = isset($data['generatedFields']) ? (array) $data['generatedFields'] : null;
        $this->numberOfRecordsUpdated = isset($data['numberOfRecordsUpdated']) ? (string) $data['numberOfRecordsUpdated'] : null;
        $this->records = empty($data['records']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (array) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        })($data['records']);
    }
}
