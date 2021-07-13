<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Input\ListStreamsInput;
use AsyncAws\Kinesis\KinesisClient;

/**
 * Represents the output for `ListStreams`.
 *
 * @implements \IteratorAggregate<string>
 */
class ListStreamsOutput extends Result implements \IteratorAggregate
{
    /**
     * The names of the streams that are associated with the AWS account making the `ListStreams` request.
     */
    private $streamNames = [];

    /**
     * If set to `true`, there are more streams available to list.
     */
    private $hasMoreStreams;

    public function getHasMoreStreams(): bool
    {
        $this->initialize();

        return $this->hasMoreStreams;
    }

    /**
     * Iterates over StreamNames.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getStreamNames();
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getStreamNames(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->streamNames;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListStreamsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->hasMoreStreams) {
                $input->setExclusiveStartStreamName(\array_slice($page->streamNames, -1)[0]);

                $this->registerPrefetch($nextPage = $client->listStreams($input));
            } else {
                $nextPage = null;
            }

            yield from $page->streamNames;

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

        $this->streamNames = $this->populateResultStreamNameList($data['StreamNames']);
        $this->hasMoreStreams = filter_var($data['HasMoreStreams'], \FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return string[]
     */
    private function populateResultStreamNameList(array $json): array
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
