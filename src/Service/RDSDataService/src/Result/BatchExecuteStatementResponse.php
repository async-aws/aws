<?php

namespace AsyncAws\RDSDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\RDSDataService\ValueObject\UpdateResult;

class BatchExecuteStatementResponse extends Result
{
    /**
     * The execution results of each batch entry.
     */
    private $updateResults = [];

    /**
     * @return UpdateResult[]
     */
    public function getUpdateResults(): array
    {
        $this->initialize();

        return $this->updateResults;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->updateResults = empty($data['updateResults']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new UpdateResult([
                    'generatedFields' => isset($item['generatedFields']) ? (array) $item['generatedFields'] : null,
                ]);
            }

            return $items;
        })($data['updateResults']);
    }
}
