<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iot\Input\ListThingGroupsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\ValueObject\GroupNameAndArn;

/**
 * @implements \IteratorAggregate<GroupNameAndArn>
 */
class ListThingGroupsResponse extends Result implements \IteratorAggregate
{
    /**
     * The thing groups.
     */
    private $thingGroups;

    /**
     * The token to use to get the next set of results. Will not be returned if operation has returned all results.
     */
    private $nextToken;

    /**
     * Iterates over thingGroups.
     *
     * @return \Traversable<GroupNameAndArn>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getThingGroups();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<GroupNameAndArn>
     */
    public function getThingGroups(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->thingGroups;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof IotClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListThingGroupsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listThingGroups($input));
            } else {
                $nextPage = null;
            }

            yield from $page->thingGroups;

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

        $this->thingGroups = empty($data['thingGroups']) ? [] : $this->populateResultThingGroupNameAndArnList($data['thingGroups']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultGroupNameAndArn(array $json): GroupNameAndArn
    {
        return new GroupNameAndArn([
            'groupName' => isset($json['groupName']) ? (string) $json['groupName'] : null,
            'groupArn' => isset($json['groupArn']) ? (string) $json['groupArn'] : null,
        ]);
    }

    /**
     * @return GroupNameAndArn[]
     */
    private function populateResultThingGroupNameAndArnList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultGroupNameAndArn($item);
        }

        return $items;
    }
}
