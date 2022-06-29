<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iot\Input\ListThingPrincipalsRequest;
use AsyncAws\Iot\IotClient;

/**
 * The output from the ListThingPrincipals operation.
 *
 * @implements \IteratorAggregate<string>
 */
class ListThingPrincipalsResponse extends Result implements \IteratorAggregate
{
    /**
     * The principals associated with the thing.
     */
    private $principals;

    /**
     * The token to use to get the next set of results, or **null** if there are no additional results.
     */
    private $nextToken;

    /**
     * Iterates over principals.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getPrincipals();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getPrincipals(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->principals;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof IotClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListThingPrincipalsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listThingPrincipals($input));
            } else {
                $nextPage = null;
            }

            yield from $page->principals;

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

        $this->principals = empty($data['principals']) ? [] : $this->populateResultPrincipals($data['principals']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return string[]
     */
    private function populateResultPrincipals(array $json): array
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
