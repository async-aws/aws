<?php

namespace AsyncAws\Ec2\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ec2\Enum\SnapshotReturnCodes;
use AsyncAws\Ec2\ValueObject\DeleteSnapshotReturnCode;

class DeregisterImageResult extends Result
{
    /**
     * Returns `true` if the request succeeds; otherwise, it returns an error.
     *
     * @var bool|null
     */
    private $return;

    /**
     * The deletion result for each snapshot associated with the AMI, including the snapshot ID and its success or error
     * code.
     *
     * @var DeleteSnapshotReturnCode[]
     */
    private $deleteSnapshotResults;

    /**
     * @return DeleteSnapshotReturnCode[]
     */
    public function getDeleteSnapshotResults(): array
    {
        $this->initialize();

        return $this->deleteSnapshotResults;
    }

    public function getReturn(): ?bool
    {
        $this->initialize();

        return $this->return;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->return = (null !== $v = $data->return[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->deleteSnapshotResults = (0 === ($v = $data->deleteSnapshotResultSet)->count()) ? [] : $this->populateResultDeleteSnapshotResultSet($v);
    }

    /**
     * @return DeleteSnapshotReturnCode[]
     */
    private function populateResultDeleteSnapshotResultSet(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->item as $item) {
            $items[] = $this->populateResultDeleteSnapshotReturnCode($item);
        }

        return $items;
    }

    private function populateResultDeleteSnapshotReturnCode(\SimpleXMLElement $xml): DeleteSnapshotReturnCode
    {
        return new DeleteSnapshotReturnCode([
            'SnapshotId' => (null !== $v = $xml->snapshotId[0]) ? (string) $v : null,
            'ReturnCode' => (null !== $v = $xml->returnCode[0]) ? (!SnapshotReturnCodes::exists((string) $xml->returnCode) ? SnapshotReturnCodes::UNKNOWN_TO_SDK : (string) $xml->returnCode) : null,
        ]);
    }
}
