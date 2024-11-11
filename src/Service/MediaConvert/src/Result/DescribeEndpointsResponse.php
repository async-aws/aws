<?php

namespace AsyncAws\MediaConvert\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\MediaConvert\Input\DescribeEndpointsRequest;
use AsyncAws\MediaConvert\MediaConvertClient;
use AsyncAws\MediaConvert\ValueObject\Endpoint;

/**
 * Successful describe endpoints requests will return your account API endpoint.
 *
 * @implements \IteratorAggregate<Endpoint>
 */
class DescribeEndpointsResponse extends Result implements \IteratorAggregate
{
    /**
     * List of endpoints.
     *
     * @var Endpoint[]
     */
    private $endpoints;

    /**
     * Use this string to request the next batch of endpoints.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Endpoint>
     */
    public function getEndpoints(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->endpoints;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof MediaConvertClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeEndpointsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->describeEndpoints($input));
            } else {
                $nextPage = null;
            }

            yield from $page->endpoints;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Endpoints.
     *
     * @return \Traversable<Endpoint>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getEndpoints();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->endpoints = empty($data['endpoints']) ? [] : $this->populateResult__listOfEndpoint($data['endpoints']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultEndpoint(array $json): Endpoint
    {
        return new Endpoint([
            'Url' => isset($json['url']) ? (string) $json['url'] : null,
        ]);
    }

    /**
     * @return Endpoint[]
     */
    private function populateResult__listOfEndpoint(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEndpoint($item);
        }

        return $items;
    }
}
