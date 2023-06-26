<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\TimeToLiveSpecification;

class UpdateTimeToLiveOutput extends Result
{
    /**
     * Represents the output of an `UpdateTimeToLive` operation.
     *
     * @var TimeToLiveSpecification|null
     */
    private $timeToLiveSpecification;

    public function getTimeToLiveSpecification(): ?TimeToLiveSpecification
    {
        $this->initialize();

        return $this->timeToLiveSpecification;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->timeToLiveSpecification = empty($data['TimeToLiveSpecification']) ? null : $this->populateResultTimeToLiveSpecification($data['TimeToLiveSpecification']);
    }

    private function populateResultTimeToLiveSpecification(array $json): TimeToLiveSpecification
    {
        return new TimeToLiveSpecification([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'AttributeName' => (string) $json['AttributeName'],
        ]);
    }
}
