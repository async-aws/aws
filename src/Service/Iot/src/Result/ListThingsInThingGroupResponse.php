<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iot\Input\ListThingsInThingGroupRequest;
use AsyncAws\Iot\IotClient;

/**
 * @implements \IteratorAggregate<string>
 */
class ListThingsInThingGroupResponse extends Result implements \IteratorAggregate
{
    /**
     * The things in the specified thing group.
     *
     * @var string[]
     */
    private $things;

    /**
     * The token to use to get the next set of results, or **null** if there are no additional results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over things.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getThings();
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
    public function getThings(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->things;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof IotClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListThingsInThingGroupRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listThingsInThingGroup($input));
            } else {
                $nextPage = null;
            }

            yield from $page->things;

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

        $this->things = empty($data['things']) ? [] : $this->populateResultThingNameList($data['things']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return string[]
     */
    private function populateResultThingNameList(array $json): array
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
