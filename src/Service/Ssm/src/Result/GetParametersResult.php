<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ssm\ValueObject\Parameter;

class GetParametersResult extends Result
{
    /**
     * A list of details for a parameter.
     *
     * @var Parameter[]
     */
    private $parameters;

    /**
     * A list of parameters that aren't formatted correctly or don't run during an execution.
     *
     * @var string[]
     */
    private $invalidParameters;

    /**
     * @return string[]
     */
    public function getInvalidParameters(): array
    {
        $this->initialize();

        return $this->invalidParameters;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        $this->initialize();

        return $this->parameters;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->parameters = empty($data['Parameters']) ? [] : $this->populateResultParameterList($data['Parameters']);
        $this->invalidParameters = empty($data['InvalidParameters']) ? [] : $this->populateResultParameterNameList($data['InvalidParameters']);
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

    /**
     * @return Parameter[]
     */
    private function populateResultParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultParameter($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultParameterNameList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
