<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\ValueObject\TimeToLiveSpecification;

class UpdateTimeToLiveOutput extends Result
{
    /**
     * Represents the output of an `UpdateTimeToLive` operation.
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

        $this->timeToLiveSpecification = empty($data['TimeToLiveSpecification']) ? null : new TimeToLiveSpecification([
            'Enabled' => filter_var($data['TimeToLiveSpecification']['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'AttributeName' => (string) $data['TimeToLiveSpecification']['AttributeName'],
        ]);
    }
}
