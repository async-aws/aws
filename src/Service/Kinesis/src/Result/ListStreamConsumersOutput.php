<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Input\ListStreamConsumersInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\ValueObject\Consumer;

/**
 * @implements \IteratorAggregate<Consumer>
 */
class ListStreamConsumersOutput extends Result implements \IteratorAggregate
{
    /**
     * An array of JSON objects. Each object represents one registered consumer.
     *
     * @var Consumer[]
     */
    private $consumers;

    /**
     * When the number of consumers that are registered with the data stream is greater than the default value for the
     * `MaxResults` parameter, or if you explicitly specify a value for `MaxResults` that is less than the number of
     * registered consumers, the response includes a pagination token named `NextToken`. You can specify this `NextToken`
     * value in a subsequent call to `ListStreamConsumers` to list the next set of registered consumers. For more
     * information about the use of this pagination token when calling the `ListStreamConsumers` operation, see
     * ListStreamConsumersInput$NextToken.
     *
     * ! Tokens expire after 300 seconds. When you obtain a value for `NextToken` in the response to a call to
     * ! `ListStreamConsumers`, you have 300 seconds to use that value. If you specify an expired token in a call to
     * ! `ListStreamConsumers`, you get `ExpiredNextTokenException`.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Consumer>
     */
    public function getConsumers(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->consumers;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListStreamConsumersInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listStreamConsumers($input));
            } else {
                $nextPage = null;
            }

            yield from $page->consumers;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Consumers.
     *
     * @return \Traversable<Consumer>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getConsumers();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->consumers = empty($data['Consumers']) ? [] : $this->populateResultConsumerList($data['Consumers']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    private function populateResultConsumer(array $json): Consumer
    {
        return new Consumer([
            'ConsumerName' => (string) $json['ConsumerName'],
            'ConsumerARN' => (string) $json['ConsumerARN'],
            'ConsumerStatus' => (string) $json['ConsumerStatus'],
            'ConsumerCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['ConsumerCreationTimestamp'])),
        ]);
    }

    /**
     * @return Consumer[]
     */
    private function populateResultConsumerList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultConsumer($item);
        }

        return $items;
    }
}
