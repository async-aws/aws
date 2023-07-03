<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\CodeCommit\ValueObject\BranchInfo;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a get branch operation.
 */
class GetBranchOutput extends Result
{
    /**
     * The name of the branch.
     *
     * @var BranchInfo|null
     */
    private $branch;

    public function getBranch(): ?BranchInfo
    {
        $this->initialize();

        return $this->branch;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->branch = empty($data['branch']) ? null : $this->populateResultBranchInfo($data['branch']);
    }

    private function populateResultBranchInfo(array $json): BranchInfo
    {
        return new BranchInfo([
            'branchName' => isset($json['branchName']) ? (string) $json['branchName'] : null,
            'commitId' => isset($json['commitId']) ? (string) $json['commitId'] : null,
        ]);
    }
}
