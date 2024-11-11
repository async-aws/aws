<?php

namespace AsyncAws\Iot\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iot\Input\ListThingTypesRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\ValueObject\ThingTypeDefinition;
use AsyncAws\Iot\ValueObject\ThingTypeMetadata;
use AsyncAws\Iot\ValueObject\ThingTypeProperties;

/**
 * The output for the ListThingTypes operation.
 *
 * @implements \IteratorAggregate<ThingTypeDefinition>
 */
class ListThingTypesResponse extends Result implements \IteratorAggregate
{
    /**
     * The thing types.
     *
     * @var ThingTypeDefinition[]
     */
    private $thingTypes;

    /**
     * The token for the next set of results. Will not be returned if operation has returned all results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over thingTypes.
     *
     * @return \Traversable<ThingTypeDefinition>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getThingTypes();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ThingTypeDefinition>
     */
    public function getThingTypes(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->thingTypes;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof IotClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListThingTypesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listThingTypes($input));
            } else {
                $nextPage = null;
            }

            yield from $page->thingTypes;

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

        $this->thingTypes = empty($data['thingTypes']) ? [] : $this->populateResultThingTypeList($data['thingTypes']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return string[]
     */
    private function populateResultSearchableAttributes(array $json): array
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

    private function populateResultThingTypeDefinition(array $json): ThingTypeDefinition
    {
        return new ThingTypeDefinition([
            'thingTypeName' => isset($json['thingTypeName']) ? (string) $json['thingTypeName'] : null,
            'thingTypeArn' => isset($json['thingTypeArn']) ? (string) $json['thingTypeArn'] : null,
            'thingTypeProperties' => empty($json['thingTypeProperties']) ? null : $this->populateResultThingTypeProperties($json['thingTypeProperties']),
            'thingTypeMetadata' => empty($json['thingTypeMetadata']) ? null : $this->populateResultThingTypeMetadata($json['thingTypeMetadata']),
        ]);
    }

    /**
     * @return ThingTypeDefinition[]
     */
    private function populateResultThingTypeList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultThingTypeDefinition($item);
        }

        return $items;
    }

    private function populateResultThingTypeMetadata(array $json): ThingTypeMetadata
    {
        return new ThingTypeMetadata([
            'deprecated' => isset($json['deprecated']) ? filter_var($json['deprecated'], \FILTER_VALIDATE_BOOLEAN) : null,
            'deprecationDate' => isset($json['deprecationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['deprecationDate']))) ? $d : null,
            'creationDate' => isset($json['creationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['creationDate']))) ? $d : null,
        ]);
    }

    private function populateResultThingTypeProperties(array $json): ThingTypeProperties
    {
        return new ThingTypeProperties([
            'thingTypeDescription' => isset($json['thingTypeDescription']) ? (string) $json['thingTypeDescription'] : null,
            'searchableAttributes' => !isset($json['searchableAttributes']) ? null : $this->populateResultSearchableAttributes($json['searchableAttributes']),
        ]);
    }
}
