<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\CodeCommit\ValueObject\RepositoryMetadata;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a create repository operation.
 */
class CreateRepositoryOutput extends Result
{
    /**
     * Information about the newly created repository.
     */
    private $repositoryMetadata;

    public function getRepositoryMetadata(): ?RepositoryMetadata
    {
        $this->initialize();

        return $this->repositoryMetadata;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->repositoryMetadata = empty($data['repositoryMetadata']) ? null : $this->populateResultRepositoryMetadata($data['repositoryMetadata']);
    }

    private function populateResultRepositoryMetadata(array $json): RepositoryMetadata
    {
        return new RepositoryMetadata([
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
            'repositoryId' => isset($json['repositoryId']) ? (string) $json['repositoryId'] : null,
            'repositoryName' => isset($json['repositoryName']) ? (string) $json['repositoryName'] : null,
            'repositoryDescription' => isset($json['repositoryDescription']) ? (string) $json['repositoryDescription'] : null,
            'defaultBranch' => isset($json['defaultBranch']) ? (string) $json['defaultBranch'] : null,
            'lastModifiedDate' => (isset($json['lastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['lastModifiedDate'])))) ? $d : null,
            'creationDate' => (isset($json['creationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['creationDate'])))) ? $d : null,
            'cloneUrlHttp' => isset($json['cloneUrlHttp']) ? (string) $json['cloneUrlHttp'] : null,
            'cloneUrlSsh' => isset($json['cloneUrlSsh']) ? (string) $json['cloneUrlSsh'] : null,
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
        ]);
    }
}
