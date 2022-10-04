<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\CodeCommit\ValueObject\Commit;
use AsyncAws\CodeCommit\ValueObject\UserInfo;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a get commit operation.
 */
class GetCommitOutput extends Result
{
    /**
     * A commit data type object that contains information about the specified commit.
     */
    private $commit;

    public function getCommit(): Commit
    {
        $this->initialize();

        return $this->commit;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->commit = $this->populateResultCommit($data['commit']);
    }

    private function populateResultCommit(array $json): Commit
    {
        return new Commit([
            'commitId' => isset($json['commitId']) ? (string) $json['commitId'] : null,
            'treeId' => isset($json['treeId']) ? (string) $json['treeId'] : null,
            'parents' => !isset($json['parents']) ? null : $this->populateResultParentList($json['parents']),
            'message' => isset($json['message']) ? (string) $json['message'] : null,
            'author' => empty($json['author']) ? null : $this->populateResultUserInfo($json['author']),
            'committer' => empty($json['committer']) ? null : $this->populateResultUserInfo($json['committer']),
            'additionalData' => isset($json['additionalData']) ? (string) $json['additionalData'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultParentList(array $json): array
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

    private function populateResultUserInfo(array $json): UserInfo
    {
        return new UserInfo([
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'email' => isset($json['email']) ? (string) $json['email'] : null,
            'date' => isset($json['date']) ? (string) $json['date'] : null,
        ]);
    }
}
