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

        $this->parameter = empty($data['Parameter']) ? null : $this->populateResultParameter($data['Parameter']);
    }

    private function populateResultParameter(array $json): Parameter
    {
        return new Parameter([
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
            'Version' => isset($json['Version']) ? (int) $json['Version'] : null,
            'Selector' => isset($json['Selector']) ? (string) $json['Selector'] : null,
            'SourceResult' => isset($json['SourceResult']) ? (string) $json['SourceResult'] : null,
            'LastModifiedDate' => (isset($json['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['LastModifiedDate'])))) ? $d : null,
            'ARN' => isset($json['ARN']) ? (string) $json['ARN'] : null,
            'DataType' => isset($json['DataType']) ? (string) $json['DataType'] : null,
        ]);
    }
}
