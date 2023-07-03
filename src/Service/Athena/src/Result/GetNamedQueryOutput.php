<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\NamedQuery;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetNamedQueryOutput extends Result
{
    /**
     * Information about the query.
     *
     * @var NamedQuery|null
     */
    private $namedQuery;

    public function getNamedQuery(): ?NamedQuery
    {
        $this->initialize();

        return $this->namedQuery;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->namedQuery = empty($data['NamedQuery']) ? null : $this->populateResultNamedQuery($data['NamedQuery']);
    }

    private function populateResultNamedQuery(array $json): NamedQuery
    {
        return new NamedQuery([
            'Name' => (string) $json['Name'],
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'Database' => (string) $json['Database'],
            'QueryString' => (string) $json['QueryString'],
            'NamedQueryId' => isset($json['NamedQueryId']) ? (string) $json['NamedQueryId'] : null,
            'WorkGroup' => isset($json['WorkGroup']) ? (string) $json['WorkGroup'] : null,
        ]);
    }
}
