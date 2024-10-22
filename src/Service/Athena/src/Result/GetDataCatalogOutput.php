<?php

namespace AsyncAws\Athena\Result;

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
            'Type' => (string) $json['Type'],
            'Parameters' => !isset($json['Parameters']) ? null : $this->populateResultParametersMap($json['Parameters']),
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
