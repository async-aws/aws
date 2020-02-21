<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Result;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\LambdaClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ListLayerVersionsResponse extends Result implements \IteratorAggregate
{
    /**
     * A pagination token returned when the response doesn't contain all versions.
     */
    private $NextMarker;

    /**
     * A list of versions.
     */
    private $LayerVersions = [];

    /**
     * @var self[]
     */
    private $prefetch = [];

    /**
     * Ensure current request is resolved and right exception is thrown.
     */
    public function __destruct()
    {
        while (!empty($this->prefetch)) {
            array_shift($this->prefetch)->cancel();
        }

        $this->resolve();
    }

    /**
     * Iterates over LayerVersions.
     *
     * @return \Traversable<LayerVersionsListItem>
     */
    public function getIterator(): \Traversable
    {
        if (!$this->awsClient instanceof LambdaClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListLayerVersionsRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextMarker()) {
                $input->setMarker($page->getNextMarker());

                $nextPage = $this->awsClient->ListLayerVersions($input);
                $this->prefetch[spl_object_hash($nextPage)] = $nextPage;
            } else {
                $nextPage = null;
            }

            yield from $page->getLayerVersions(true);

            if (null === $nextPage) {
                break;
            }

            unset($this->prefetch[spl_object_hash($nextPage)]);
            $page = $nextPage;
        }
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<LayerVersionsListItem>
     */
    public function getLayerVersions(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->LayerVersions;

            return;
        }

        if (!$this->awsClient instanceof LambdaClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListLayerVersionsRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextMarker()) {
                $input->setMarker($page->getNextMarker());

                $nextPage = $this->awsClient->ListLayerVersions($input);
                $this->prefetch[spl_object_hash($nextPage)] = $nextPage;
            } else {
                $nextPage = null;
            }

            yield from $page->getLayerVersions(true);

            if (null === $nextPage) {
                break;
            }

            unset($this->prefetch[spl_object_hash($nextPage)]);
            $page = $nextPage;
        }
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->NextMarker;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $this->NextMarker = ($v = $data->NextMarker) ? (string) $v : null;
        $this->LayerVersions = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = new LayerVersionsListItem([
                    'LayerVersionArn' => ($v = $item->LayerVersionArn) ? (string) $v : null,
                    'Version' => ($v = $item->Version) ? (string) $v : null,
                    'Description' => ($v = $item->Description) ? (string) $v : null,
                    'CreatedDate' => ($v = $item->CreatedDate) ? (string) $v : null,
                    'CompatibleRuntimes' => (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml as $item) {
                            $a = ($v = $item) ? (string) $v : null;
                            if (null !== $a) {
                                $items[] = $a;
                            }
                        }

                        return $items;
                    })($item->CompatibleRuntimes),
                    'LicenseInfo' => ($v = $item->LicenseInfo) ? (string) $v : null,
                ]);
            }

            return $items;
        })($data->LayerVersions);
    }
}
