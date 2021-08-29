<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ssm\ValueObject\Parameter;

class GetParametersResult extends Result
{
    /**
     * A list of details for a parameter.
     */
    private $parameters;

    /**
     * A list of parameters that aren't formatted correctly or don't run during an execution.
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

    /**
     * @return Parameter[]
     */
    private function populateResultParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Parameter([
                'Name' => isset($item['Name']) ? (string) $item['Name'] : null,
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
                'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
                'Version' => isset($item['Version']) ? (string) $item['Version'] : null,
                'Selector' => isset($item['Selector']) ? (string) $item['Selector'] : null,
                'SourceResult' => isset($item['SourceResult']) ? (string) $item['SourceResult'] : null,
                'LastModifiedDate' => (isset($item['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['LastModifiedDate'])))) ? $d : null,
                'ARN' => isset($item['ARN']) ? (string) $item['ARN'] : null,
                'DataType' => isset($item['DataType']) ? (string) $item['DataType'] : null,
            ]);
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
