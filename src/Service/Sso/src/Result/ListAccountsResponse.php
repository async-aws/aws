<?php

namespace AsyncAws\Sso\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sso\Input\ListAccountsRequest;
use AsyncAws\Sso\SsoClient;
use AsyncAws\Sso\ValueObject\AccountInfo;

/**
 * @implements \IteratorAggregate<AccountInfo>
 */
class ListAccountsResponse extends Result implements \IteratorAggregate
{
    /**
     * The page token client that is used to retrieve the list of accounts.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * A paginated response with the list of account information and the next token if more results are available.
     *
     * @var AccountInfo[]
     */
    private $accountList;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<AccountInfo>
     */
    public function getAccountList(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->accountList;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SsoClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListAccountsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listAccounts($input));
            } else {
                $nextPage = null;
            }

            yield from $page->accountList;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over accountList.
     *
     * @return \Traversable<AccountInfo>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getAccountList();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
        $this->accountList = empty($data['accountList']) ? [] : $this->populateResultAccountListType($data['accountList']);
    }

    private function populateResultAccountInfo(array $json): AccountInfo
    {
        return new AccountInfo([
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
            'accountName' => isset($json['accountName']) ? (string) $json['accountName'] : null,
            'emailAddress' => isset($json['emailAddress']) ? (string) $json['emailAddress'] : null,
        ]);
    }

    /**
     * @return AccountInfo[]
     */
    private function populateResultAccountListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAccountInfo($item);
        }

        return $items;
    }
}
