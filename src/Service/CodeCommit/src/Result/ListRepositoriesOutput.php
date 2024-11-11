<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\ListRepositoriesInput;
use AsyncAws\CodeCommit\ValueObject\RepositoryNameIdPair;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a list repositories operation.
 *
 * @implements \IteratorAggregate<RepositoryNameIdPair>
 */
class ListRepositoriesOutput extends Result implements \IteratorAggregate
{
    /**
     * Lists the repositories called by the list repositories operation.
     *
     * @var RepositoryNameIdPair[]
     */
    private $repositories;

    /**
     * An enumeration token that allows the operation to batch the results of the operation. Batch sizes are 1,000 for list
     * repository operations. When the client sends the token back to CodeCommit, another page of 1,000 records is
     * retrieved.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over repositories.
     *
     * @return \Traversable<RepositoryNameIdPair>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getRepositories();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<RepositoryNameIdPair>
     */
    public function getRepositories(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->repositories;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CodeCommitClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListRepositoriesInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listRepositories($input));
            } else {
                $nextPage = null;
            }

            yield from $page->repositories;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->repositories = empty($data['repositories']) ? [] : $this->populateResultRepositoryNameIdPairList($data['repositories']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultRepositoryNameIdPair(array $json): RepositoryNameIdPair
    {
        return new RepositoryNameIdPair([
            'repositoryName' => isset($json['repositoryName']) ? (string) $json['repositoryName'] : null,
            'repositoryId' => isset($json['repositoryId']) ? (string) $json['repositoryId'] : null,
        ]);
    }

    /**
     * @return RepositoryNameIdPair[]
     */
    private function populateResultRepositoryNameIdPairList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultRepositoryNameIdPair($item);
        }

        return $items;
    }
}
