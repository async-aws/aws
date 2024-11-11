<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sns\Input\ListTopicsInput;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\ValueObject\Topic;

/**
 * Response for ListTopics action.
 *
 * @implements \IteratorAggregate<Topic>
 */
class ListTopicsResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of topic ARNs.
     *
     * @var Topic[]
     */
    private $topics;

    /**
     * Token to pass along to the next `ListTopics` request. This element is returned if there are additional topics to
     * retrieve.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over Topics.
     *
     * @return \Traversable<Topic>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getTopics();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Topic>
     */
    public function getTopics(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->topics;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SnsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListTopicsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listTopics($input));
            } else {
                $nextPage = null;
            }

            yield from $page->topics;

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
        $data = $data->ListTopicsResult;

        $this->topics = (0 === ($v = $data->Topics)->count()) ? [] : $this->populateResultTopicsList($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
    }

    private function populateResultTopic(\SimpleXMLElement $xml): Topic
    {
        return new Topic([
            'TopicArn' => (null !== $v = $xml->TopicArn[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Topic[]
     */
    private function populateResultTopicsList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultTopic($item);
        }

        return $items;
    }
}
