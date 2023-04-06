<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\Database;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetDatabaseOutput extends Result
{
    /**
     * The database returned.
     */
    private $database;

    public function getDatabase(): ?Database
    {
        $this->initialize();

        return $this->database;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->database = empty($data['Database']) ? null : $this->populateResultDatabase($data['Database']);
    }

    private function populateResultDatabase(array $json): Database
    {
        return new Database([
            'Name' => (string) $json['Name'],
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
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
