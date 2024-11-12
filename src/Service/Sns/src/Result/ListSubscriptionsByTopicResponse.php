<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sns\Input\ListSubscriptionsByTopicInput;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\ValueObject\Subscription;

/**
 * Response for ListSubscriptionsByTopic action.
 *
 * @implements \IteratorAggregate<Subscription>
 */
class ListSubscriptionsByTopicResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of subscriptions.
     *
     * @var Subscription[]
     */
    private $subscriptions;

    /**
     * Token to pass along to the next `ListSubscriptionsByTopic` request. This element is returned if there are more
     * subscriptions to retrieve.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over Subscriptions.
     *
     * @return \Traversable<Subscription>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getSubscriptions();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Subscription>
     */
    public function getSubscriptions(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->subscriptions;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SnsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListSubscriptionsByTopicInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listSubscriptionsByTopic($input));
            } else {
                $nextPage = null;
            }

            yield from $page->subscriptions;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListSubscriptionsByTopicResult;

        $this->subscriptions = (0 === ($v = $data->Subscriptions)->count()) ? [] : $this->populateResultSubscriptionsList($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
    }

    private function populateResultSubscription(\SimpleXMLElement $xml): Subscription
    {
        return new Subscription([
            'SubscriptionArn' => (null !== $v = $xml->SubscriptionArn[0]) ? (string) $v : null,
            'Owner' => (null !== $v = $xml->Owner[0]) ? (string) $v : null,
            'Protocol' => (null !== $v = $xml->Protocol[0]) ? (string) $v : null,
            'Endpoint' => (null !== $v = $xml->Endpoint[0]) ? (string) $v : null,
            'TopicArn' => (null !== $v = $xml->TopicArn[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Subscription[]
     */
    private function populateResultSubscriptionsList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultSubscription($item);
        }

        return $items;
    }
}
