<?php

namespace AsyncAws\Scheduler\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Scheduler\Enum\ScheduleState;
use AsyncAws\Scheduler\Input\ListSchedulesInput;
use AsyncAws\Scheduler\SchedulerClient;
use AsyncAws\Scheduler\ValueObject\ScheduleSummary;
use AsyncAws\Scheduler\ValueObject\TargetSummary;

/**
 * @implements \IteratorAggregate<ScheduleSummary>
 */
class ListSchedulesOutput extends Result implements \IteratorAggregate
{
    /**
     * Indicates whether there are additional results to retrieve. If the value is null, there are no more results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The schedules that match the specified criteria.
     *
     * @var ScheduleSummary[]
     */
    private $schedules;

    /**
     * Iterates over Schedules.
     *
     * @return \Traversable<ScheduleSummary>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getSchedules();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ScheduleSummary>
     */
    public function getSchedules(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->schedules;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SchedulerClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListSchedulesInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listSchedules($input));
            } else {
                $nextPage = null;
            }

            yield from $page->schedules;

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
        $this->schedules = $this->populateResultScheduleList($data['Schedules'] ?? []);
    }

    /**
     * @return ScheduleSummary[]
     */
    private function populateResultScheduleList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultScheduleSummary($item);
        }

        return $items;
    }

    private function populateResultScheduleSummary(array $json): ScheduleSummary
    {
        return new ScheduleSummary([
            'Arn' => isset($json['Arn']) ? (string) $json['Arn'] : null,
            'CreationDate' => isset($json['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationDate']))) ? $d : null,
            'GroupName' => isset($json['GroupName']) ? (string) $json['GroupName'] : null,
            'LastModificationDate' => isset($json['LastModificationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModificationDate']))) ? $d : null,
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'State' => isset($json['State']) ? (!ScheduleState::exists((string) $json['State']) ? ScheduleState::UNKNOWN_TO_SDK : (string) $json['State']) : null,
            'Target' => empty($json['Target']) ? null : $this->populateResultTargetSummary($json['Target']),
        ]);
    }

    private function populateResultTargetSummary(array $json): TargetSummary
    {
        return new TargetSummary([
            'Arn' => (string) $json['Arn'],
        ]);
    }
}
