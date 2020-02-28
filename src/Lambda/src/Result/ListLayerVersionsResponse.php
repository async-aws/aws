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
     * Iterates over LayerVersions.
     *
     * @return \Traversable<LayerVersionsListItem>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
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

                $this->registerPrefetch($nextPage = $client->ListLayerVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getLayerVersions(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
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

        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
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

                $this->registerPrefetch($nextPage = $client->ListLayerVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getLayerVersions(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
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
        $data = $response->toArray(false);

        $this->NextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->LayerVersions = !$data['LayerVersions'] ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new LayerVersionsListItem([
                    'LayerVersionArn' => isset($item['LayerVersionArn']) ? (string) $item['LayerVersionArn'] : null,
                    'Version' => isset($item['Version']) ? (string) $item['Version'] : null,
                    'Description' => isset($item['Description']) ? (string) $item['Description'] : null,
                    'CreatedDate' => isset($item['CreatedDate']) ? (string) $item['CreatedDate'] : null,
                    'CompatibleRuntimes' => !$item['CompatibleRuntimes'] ? [] : (function (array $json): array {
                        $items = [];
                        foreach ($json as $item) {
                            $a = isset($item) ? (string) $item : null;
                            if (null !== $a) {
                                $items[] = $a;
                            }
                        }

                        return $items;
                    })($item['CompatibleRuntimes']),
                    'LicenseInfo' => isset($item['LicenseInfo']) ? (string) $item['LicenseInfo'] : null,
                ]);
            }

            return $items;
        })($data['LayerVersions']);
    }
}
