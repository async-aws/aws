<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Parameter;

/**
 * @implements \IteratorAggregate<Parameter>
 */
class GetParametersByPathResult extends Result implements \IteratorAggregate
{
    /**
     * A list of parameters found in the specified hierarchy.
     */
    private $Parameters = [];

    /**
     * The token for the next set of items to return. Use this token to get the next set of results.
     */
    private $NextToken;

    /**
     * Iterates over Parameters.
     *
     * @return \Traversable<Parameter>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof SsmClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetParametersByPathRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->GetParametersByPath($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getParameters(true);

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

        return $this->NextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Parameter>
     */
    public function getParameters(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->Parameters;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SsmClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetParametersByPathRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->GetParametersByPath($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getParameters(true);

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

        $this->Parameters = empty($data['Parameters']) ? [] : $this->populateResultParameterList($data['Parameters']);
        $this->NextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    /**
     * @return Parameter[]
     */
    private function populateResultParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Parameter([
                'Name' => isset($item['Name']) ? (string) $item['Name'] : null,
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
                'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
                'Version' => isset($item['Version']) ? (string) $item['Version'] : null,
                'Selector' => isset($item['Selector']) ? (string) $item['Selector'] : null,
                'SourceResult' => isset($item['SourceResult']) ? (string) $item['SourceResult'] : null,
                'LastModifiedDate' => (isset($item['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item['LastModifiedDate'])))) ? $d : null,
                'ARN' => isset($item['ARN']) ? (string) $item['ARN'] : null,
                'DataType' => isset($item['DataType']) ? (string) $item['DataType'] : null,
            ]);
        }

        return $items;
    }
}
