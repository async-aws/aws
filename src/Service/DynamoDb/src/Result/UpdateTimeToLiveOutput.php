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
    private $TimeToLiveSpecification;

    public function getTimeToLiveSpecification(): ?TimeToLiveSpecification
    {
        $this->initialize();

        return $this->TimeToLiveSpecification;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->TimeToLiveSpecification = empty($data['TimeToLiveSpecification']) ? null : new TimeToLiveSpecification([
            'Enabled' => filter_var($data['TimeToLiveSpecification']['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'AttributeName' => (string) $data['TimeToLiveSpecification']['AttributeName'],
        ]);
    }
}
