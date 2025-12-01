<?php

namespace AsyncAws\Scheduler\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Scheduler\Enum\ScheduleGroupState;
use AsyncAws\Scheduler\Input\ListScheduleGroupsInput;
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\ScheduleGroupSummary;

/**
 * @implements \IteratorAggregate<ScheduleGroupSummary>
 */
class ListScheduleGroupsOutput extends Result implements \IteratorAggregate
{
    /**
     * Indicates whether there are additional results to retrieve. If the value is null, there are no more results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The schedule groups that match the specified criteria.
     *
     * @var ScheduleGroupSummary[]
     */
    private $scheduleGroups;

    /**
     * Iterates over ScheduleGroups.
     *
     * @return \Traversable<ScheduleGroupSummary>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getScheduleGroups();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ScheduleGroupSummary>
     */
    public function getScheduleGroups(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->scheduleGroups;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SchedulerClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListScheduleGroupsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listScheduleGroups($input));
            } else {
                $nextPage = null;
            }

            yield from $page->scheduleGroups;

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

        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
        $this->scheduleGroups = $this->populateResultScheduleGroupList($data['ScheduleGroups'] ?? []);
    }

    /**
     * @return ScheduleGroupSummary[]
     */
    private function populateResultScheduleGroupList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultScheduleGroupSummary($item);
        }

        return $items;
    }

    private function populateResultScheduleGroupSummary(array $json): ScheduleGroupSummary
    {
        return new ScheduleGroupSummary([
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
            'CreationDate' => isset($json['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationDate']))) ? $d : null,
            'LastModificationDate' => isset($json['LastModificationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModificationDate']))) ? $d : null,
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'State' => isset($json['State']) ? (!ScheduleGroupState::exists((string) $json['State']) ? ScheduleGroupState::UNKNOWN_TO_SDK : (string) $json['State']) : null,
        ]);
    }
}
