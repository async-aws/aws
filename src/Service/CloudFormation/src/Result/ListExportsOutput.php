<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\ListExportsInput;
use AsyncAws\CloudFormation\ValueObject\Export;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<Export>
 */
class ListExportsOutput extends Result implements \IteratorAggregate
{
    /**
     * The output for the ListExports action.
     *
     * @var Export[]
     */
    private $exports;

    /**
     * If the output exceeds 100 exported output values, a string that identifies the next page of exports. If there is no
     * additional page, this value is null.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Export>
     */
    public function getExports(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->exports;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListExportsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listExports($input));
            } else {
                $nextPage = null;
            }

            yield from $page->exports;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Exports.
     *
     * @return \Traversable<Export>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getExports();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListExportsResult;

        $this->exports = (0 === ($v = $data->Exports)->count()) ? [] : $this->populateResultExports($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
    }

    private function populateResultExport(\SimpleXMLElement $xml): Export
    {
        return new Export([
            'ExportingStackId' => (null !== $v = $xml->ExportingStackId[0]) ? (string) $v : null,
            'Name' => (null !== $v = $xml->Name[0]) ? (string) $v : null,
            'Value' => (null !== $v = $xml->Value[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Export[]
     */
    private function populateResultExports(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultExport($item);
        }

        return $items;
    }
}
