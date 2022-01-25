<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteParametersResult extends Result
{
    /**
     * The names of the deleted parameters.
     */
    private $deletedParameters;

    /**
     * The names of parameters that weren't deleted because the parameters aren't valid.
     */
    private $invalidParameters;

    /**
     * @return string[]
     */
    public function getDeletedParameters(): array
    {
        $this->initialize();

        return $this->deletedParameters;
    }

    /**
     * @return string[]
     */
    public function getInvalidParameters(): array
    {
        $this->initialize();

        return $this->invalidParameters;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->deletedParameters = empty($data['DeletedParameters']) ? [] : $this->populateResultParameterNameList($data['DeletedParameters']);
        $this->invalidParameters = empty($data['InvalidParameters']) ? [] : $this->populateResultParameterNameList($data['InvalidParameters']);
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
