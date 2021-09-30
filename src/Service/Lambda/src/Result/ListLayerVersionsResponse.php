<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\Runtime;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\ValueObject\LayerVersionsListItem;

/**
 * @implements \IteratorAggregate<LayerVersionsListItem>
 */
class ListLayerVersionsResponse extends Result implements \IteratorAggregate
{
    /**
     * A pagination token returned when the response doesn't contain all versions.
     */
    private $nextMarker;

    /**
     * A list of versions.
     */
    private $layerVersions;

    /**
     * Iterates over LayerVersions.
     *
     * @return \Traversable<LayerVersionsListItem>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getLayerVersions();
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
            yield from $this->layerVersions;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListLayerVersionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextMarker) {
                $input->setMarker($page->nextMarker);

                $this->registerPrefetch($nextPage = $client->listLayerVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->layerVersions;

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

        return $this->nextMarker;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->layerVersions = empty($data['LayerVersions']) ? [] : $this->populateResultLayerVersionsList($data['LayerVersions']);
    }

    /**
     * @return list<Architecture::*>
     */
    private function populateResultCompatibleArchitectures(array $json): array
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

    /**
     * @return list<Runtime::*>
     */
    private function populateResultCompatibleRuntimes(array $json): array
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

    /**
     * @return LayerVersionsListItem[]
     */
    private function populateResultLayerVersionsList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new LayerVersionsListItem([
                'LayerVersionArn' => isset($item['LayerVersionArn']) ? (string) $item['LayerVersionArn'] : null,
                'Version' => isset($item['Version']) ? (string) $item['Version'] : null,
                'Description' => isset($item['Description']) ? (string) $item['Description'] : null,
                'CreatedDate' => isset($item['CreatedDate']) ? (string) $item['CreatedDate'] : null,
                'CompatibleRuntimes' => !isset($item['CompatibleRuntimes']) ? null : $this->populateResultCompatibleRuntimes($item['CompatibleRuntimes']),
                'LicenseInfo' => isset($item['LicenseInfo']) ? (string) $item['LicenseInfo'] : null,
                'CompatibleArchitectures' => !isset($item['CompatibleArchitectures']) ? null : $this->populateResultCompatibleArchitectures($item['CompatibleArchitectures']),
            ]);
        }

        return $items;
    }
}
