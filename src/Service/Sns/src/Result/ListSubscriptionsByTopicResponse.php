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
     */
    private $subscriptions = [];

    /**
     * Token to pass along to the next `ListSubscriptionsByTopic` request. This element is returned if there are more
     * subscriptions to retrieve.
     */
    private $nextToken;

    /**
     * Iterates over Subscriptions.
     *
     * @return \Traversable<Subscription>
     */
    public function getIterator(): \Traversable
    {
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
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->listSubscriptionsByTopic($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getSubscriptions(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
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
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->listSubscriptionsByTopic($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getSubscriptions(true);

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

        $this->subscriptions = !$data->Subscriptions ? [] : $this->populateResultSubscriptionsList($data->Subscriptions);
        $this->nextToken = ($v = $data->NextToken) ? (string) $v : null;
    }

    /**
     * @return Subscription[]
     */
    private function populateResultSubscriptionsList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Subscription([
                'SubscriptionArn' => ($v = $item->SubscriptionArn) ? (string) $v : null,
                'Owner' => ($v = $item->Owner) ? (string) $v : null,
                'Protocol' => ($v = $item->Protocol) ? (string) $v : null,
                'Endpoint' => ($v = $item->Endpoint) ? (string) $v : null,
                'TopicArn' => ($v = $item->TopicArn) ? (string) $v : null,
            ]);
        }

        return $items;
    }
}
