<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Input\ListAliasesRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\ValueObject\AliasConfiguration;
use AsyncAws\Lambda\ValueObject\AliasRoutingConfiguration;

/**
 * @implements \IteratorAggregate<AliasConfiguration>
 */
class ListAliasesResponse extends Result implements \IteratorAggregate
{
    /**
     * The pagination token that's included if more results are available.
     *
     * @var string|null
     */
    private $nextMarker;

    /**
     * A list of aliases.
     *
     * @var AliasConfiguration[]
     */
    private $aliases;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<AliasConfiguration>
     */
    public function getAliases(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->aliases;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListAliasesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextMarker) {
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
     * @return \Traversable<AliasConfiguration>
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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->aliases = empty($data['Aliases']) ? [] : $this->populateResultAliasList($data['Aliases']);
    }

    /**
     * @return array<string, float>
     */
    private function populateResultAdditionalVersionWeights(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (float) $value;
        }

        return $items;
    }

    private function populateResultAliasConfiguration(array $json): AliasConfiguration
    {
        return new AliasConfiguration([
            'AliasArn' => isset($json['AliasArn']) ? (string) $json['AliasArn'] : null,
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'FunctionVersion' => isset($json['FunctionVersion']) ? (string) $json['FunctionVersion'] : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'RoutingConfig' => empty($json['RoutingConfig']) ? null : $this->populateResultAliasRoutingConfiguration($json['RoutingConfig']),
            'RevisionId' => isset($json['RevisionId']) ? (string) $json['RevisionId'] : null,
        ]);
    }

    /**
     * @return AliasConfiguration[]
     */
    private function populateResultAliasList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAliasConfiguration($item);
        }

        return $items;
    }

    private function populateResultAliasRoutingConfiguration(array $json): AliasRoutingConfiguration
    {
        return new AliasRoutingConfiguration([
            'AdditionalVersionWeights' => !isset($json['AdditionalVersionWeights']) ? null : $this->populateResultAdditionalVersionWeights($json['AdditionalVersionWeights']),
        ]);
    }
}
