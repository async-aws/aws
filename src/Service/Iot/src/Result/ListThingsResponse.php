<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iot\Input\ListThingsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\ValueObject\ThingAttribute;

/**
 * The output from the ListThings operation.
 *
 * @implements \IteratorAggregate<ThingAttribute>
 */
class ListThingsResponse extends Result implements \IteratorAggregate
{
    /**
     * The things.
     *
     * @var ThingAttribute[]
     */
    private $things;

    /**
     * The token to use to get the next set of results. Will not be returned if operation has returned all results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over things.
     *
     * @return \Traversable<ThingAttribute>
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
     * @return iterable<ThingAttribute>
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
        if (!$this->input instanceof ListThingsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listThings($input));
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

        $this->things = empty($data['things']) ? [] : $this->populateResultThingAttributeList($data['things']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return array<string, string>
     */
    private function populateResultAttributes(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultThingAttribute(array $json): ThingAttribute
    {
        return new ThingAttribute([
            'thingName' => isset($json['thingName']) ? (string) $json['thingName'] : null,
            'thingTypeName' => isset($json['thingTypeName']) ? (string) $json['thingTypeName'] : null,
            'thingArn' => isset($json['thingArn']) ? (string) $json['thingArn'] : null,
            'attributes' => !isset($json['attributes']) ? null : $this->populateResultAttributes($json['attributes']),
            'version' => isset($json['version']) ? (int) $json['version'] : null,
        ]);
    }

    /**
     * @return ThingAttribute[]
     */
    private function populateResultThingAttributeList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultThingAttribute($item);
        }

        return $items;
    }
}
