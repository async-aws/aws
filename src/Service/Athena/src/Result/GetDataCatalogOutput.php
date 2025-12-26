<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\ConnectionType;
use AsyncAws\Athena\Enum\DataCatalogStatus;
use AsyncAws\Athena\Enum\DataCatalogType;
use AsyncAws\Athena\ValueObject\DataCatalog;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetDataCatalogOutput extends Result
{
    /**
     * The data catalog returned.
     *
     * @var DataCatalog|null
     */
    private $dataCatalog;

    public function getDataCatalog(): ?DataCatalog
    {
        $this->initialize();

        return $this->dataCatalog;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->dataCatalog = empty($data['DataCatalog']) ? null : $this->populateResultDataCatalog($data['DataCatalog']);
    }

    private function populateResultDataCatalog(array $json): DataCatalog
    {
        return new DataCatalog([
            'Name' => (string) $json['Name'],
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'Type' => !DataCatalogType::exists((string) $json['Type']) ? DataCatalogType::UNKNOWN_TO_SDK : (string) $json['Type'],
            'Parameters' => !isset($json['Parameters']) ? null : $this->populateResultParametersMap($json['Parameters']),
            'Status' => isset($json['Status']) ? (!DataCatalogStatus::exists((string) $json['Status']) ? DataCatalogStatus::UNKNOWN_TO_SDK : (string) $json['Status']) : null,
            'ConnectionType' => isset($json['ConnectionType']) ? (!ConnectionType::exists((string) $json['ConnectionType']) ? ConnectionType::UNKNOWN_TO_SDK : (string) $json['ConnectionType']) : null,
            'Error' => isset($json['Error']) ? (string) $json['Error'] : null,
        ]);
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
}
