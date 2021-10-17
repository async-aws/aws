<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ssm\ValueObject\Parameter;

class GetParameterResult extends Result
{
    /**
     * Information about a parameter.
     */
    private $parameter;

    public function getParameter(): ?Parameter
    {
        $this->initialize();

        return $this->parameter;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->parameter = empty($data['Parameter']) ? null : new Parameter([
            'Name' => isset($data['Parameter']['Name']) ? (string) $data['Parameter']['Name'] : null,
            'Type' => isset($data['Parameter']['Type']) ? (string) $data['Parameter']['Type'] : null,
            'Value' => isset($data['Parameter']['Value']) ? (string) $data['Parameter']['Value'] : null,
            'Version' => isset($data['Parameter']['Version']) ? (string) $data['Parameter']['Version'] : null,
            'Selector' => isset($data['Parameter']['Selector']) ? (string) $data['Parameter']['Selector'] : null,
            'SourceResult' => isset($data['Parameter']['SourceResult']) ? (string) $data['Parameter']['SourceResult'] : null,
            'LastModifiedDate' => (isset($data['Parameter']['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['Parameter']['LastModifiedDate'])))) ? $d : null,
            'ARN' => isset($data['Parameter']['ARN']) ? (string) $data['Parameter']['ARN'] : null,
            'DataType' => isset($data['Parameter']['DataType']) ? (string) $data['Parameter']['DataType'] : null,
        ]);
    }
}
