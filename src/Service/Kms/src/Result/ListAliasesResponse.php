<?php

namespace AsyncAws\Kms\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kms\Input\ListAliasesRequest;
use AsyncAws\Kms\KmsClient;
use AsyncAws\Kms\ValueObject\AliasListEntry;

/**
 * @implements \IteratorAggregate<AliasListEntry>
 */
class ListAliasesResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of aliases.
     */
    private $aliases;

    /**
     * When `Truncated` is true, this element is present and contains the value to use for the `Marker` parameter in a
     * subsequent request.
     */
    private $nextMarker;

    /**
     * A flag that indicates whether there are more items in the list. When this value is true, the list in this response is
     * truncated. To get more items, pass the value of the `NextMarker` element in thisresponse to the `Marker` parameter in
     * a subsequent request.
     */
    private $truncated;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<AliasListEntry>
     */
    public function getAliases(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->aliases;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof KmsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListAliasesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->truncated) {
                $input->setMarker($page->nextMarker);

                $this->registerPrefetch($nextPage = $client->listAliases($input));
            } else {
                $nextPage = null;
            }

            yield from $page->aliases;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Aliases.
     *
     * @return \Traversable<AliasListEntry>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getAliases();
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->nextMarker;
    }

    public function getTruncated(): ?bool
    {
        $this->initialize();

        return $this->truncated;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->aliases = empty($data['Aliases']) ? [] : $this->populateResultAliasList($data['Aliases']);
        $this->nextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->truncated = isset($data['Truncated']) ? filter_var($data['Truncated'], \FILTER_VALIDATE_BOOLEAN) : null;
    }

    /**
     * @return AliasListEntry[]
     */
    private function populateResultAliasList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new AliasListEntry([
                'AliasName' => isset($item['AliasName']) ? (string) $item['AliasName'] : null,
                'AliasArn' => isset($item['AliasArn']) ? (string) $item['AliasArn'] : null,
                'TargetKeyId' => isset($item['TargetKeyId']) ? (string) $item['TargetKeyId'] : null,
                'CreationDate' => (isset($item['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['CreationDate'])))) ? $d : null,
                'LastUpdatedDate' => (isset($item['LastUpdatedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['LastUpdatedDate'])))) ? $d : null,
            ]);
        }

        return $items;
    }
}
