<?php

namespace AsyncAws\Ssm\Result;

use AsyncAws\Core\Exception\InvalidArgument;
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
     *
     * @var Parameter[]
     */
    private $parameters;

    /**
     * The token for the next set of items to return. Use this token to get the next set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over Parameters.
     *
     * @return \Traversable<Parameter>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getParameters();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
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
            yield from $this->parameters;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SsmClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetParametersByPathRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->getParametersByPath($input));
            } else {
                $nextPage = null;
            }

            yield from $page->parameters;

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

        $this->parameters = empty($data['Parameters']) ? [] : $this->populateResultParameterList($data['Parameters']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    private function populateResultParameter(array $json): Parameter
    {
        return new Parameter([
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Type' => isset($json['Type']) ? (string) $json['Type'] : null,
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
            'Version' => isset($json['Version']) ? (int) $json['Version'] : null,
            'Selector' => isset($json['Selector']) ? (string) $json['Selector'] : null,
            'SourceResult' => isset($json['SourceResult']) ? (string) $json['SourceResult'] : null,
            'LastModifiedDate' => (isset($json['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModifiedDate'])))) ? $d : null,
            'ARN' => isset($json['ARN']) ? (string) $json['ARN'] : null,
            'DataType' => isset($json['DataType']) ? (string) $json['DataType'] : null,
        ]);
    }

    /**
     * @return Parameter[]
     */
    private function populateResultParameterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultParameter($item);
        }

        return $items;
    }
}
