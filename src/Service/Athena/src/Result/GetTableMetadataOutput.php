<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\Column;
use AsyncAws\Athena\ValueObject\TableMetadata;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetTableMetadataOutput extends Result
{
    /**
     * An object that contains table metadata.
     */
    private $tableMetadata;

    public function getTableMetadata(): ?TableMetadata
    {
        $this->initialize();

        return $this->tableMetadata;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->tableMetadata = empty($data['TableMetadata']) ? null : $this->populateResultTableMetadata($data['TableMetadata']);
    }

    private function populateResultColumn(array $json): Column
    {
        return new Column([
            'Name' => (string) $json['Name'],
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
            'Comment' => isset($json['Comment']) ? (string) $json['Comment'] : null,
        ]);
    }

    /**
     * @return Column[]
     */
    private function populateResultColumnList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultColumn($item);
        }

        return $items;
    }

    /**
     * @return array<string, string>
     */
    private function populateResultParametersMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultTableMetadata(array $json): TableMetadata
    {
        return new TableMetadata([
            'Name' => (string) $json['Name'],
            'CreateTime' => (isset($json['CreateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['CreateTime'])))) ? $d : null,
            'LastAccessTime' => (isset($json['LastAccessTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['LastAccessTime'])))) ? $d : null,
            'TableType' => isset($json['TableType']) ? (string) $json['TableType'] : null,
            'Columns' => !isset($json['Columns']) ? null : $this->populateResultColumnList($json['Columns']),
            'PartitionKeys' => !isset($json['PartitionKeys']) ? null : $this->populateResultColumnList($json['PartitionKeys']),
            'Parameters' => !isset($json['Parameters']) ? null : $this->populateResultParametersMap($json['Parameters']),
        ]);
    }
}
