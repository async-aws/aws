<?php

namespace AsyncAws\Sso\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sso\Input\ListAccountRolesRequest;
use AsyncAws\Sso\SsoClient;
use AsyncAws\Sso\ValueObject\RoleInfo;

/**
 * @implements \IteratorAggregate<RoleInfo>
 */
class ListAccountRolesResponse extends Result implements \IteratorAggregate
{
    /**
     * The page token client that is used to retrieve the list of accounts.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * A paginated response with the list of roles and the next token if more results are available.
     *
     * @var RoleInfo[]
     */
    private $roleList;

    /**
     * Iterates over roleList.
     *
     * @return \Traversable<RoleInfo>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getRoleList();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<RoleInfo>
     */
    public function getRoleList(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->roleList;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SsoClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListAccountRolesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listAccountRoles($input));
            } else {
                $nextPage = null;
            }

            yield from $page->roleList;

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

        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
        $this->roleList = empty($data['roleList']) ? [] : $this->populateResultRoleListType($data['roleList']);
    }

    private function populateResultRoleInfo(array $json): RoleInfo
    {
        return new RoleInfo([
            'roleName' => isset($json['roleName']) ? (string) $json['roleName'] : null,
            'accountId' => isset($json['accountId']) ? (string) $json['accountId'] : null,
        ]);
    }

    /**
     * @return RoleInfo[]
     */
    private function populateResultRoleListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultRoleInfo($item);
        }

        return $items;
    }
}
