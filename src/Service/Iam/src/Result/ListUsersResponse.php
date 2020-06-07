<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

/**
 * @implements \IteratorAggregate<User>
 */
class ListUsersResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of users.
     */
    private $Users = [];

    /**
     * A flag that indicates whether there are more items to return. If your results were truncated, you can make a
     * subsequent pagination request using the `Marker` request parameter to retrieve more items. Note that IAM might return
     * fewer than the `MaxItems` number of results even when there are more results available. We recommend that you check
     * `IsTruncated` after every call to ensure that you receive all your results.
     */
    private $IsTruncated;

    /**
     * When `IsTruncated` is `true`, this element is present and contains the value to use for the `Marker` parameter in a
     * subsequent pagination request.
     */
    private $Marker;

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->IsTruncated;
    }

    /**
     * Iterates over Users.
     *
     * @return \Traversable<User>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof IamClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListUsersRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getIsTruncated()) {
                $input->setMarker($page->getMarker());

                $this->registerPrefetch($nextPage = $client->ListUsers($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getUsers(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getMarker(): ?string
    {
        $this->initialize();

        return $this->Marker;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<User>
     */
    public function getUsers(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->Users;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof IamClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListUsersRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getIsTruncated()) {
                $input->setMarker($page->getMarker());

                $this->registerPrefetch($nextPage = $client->ListUsers($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getUsers(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListUsersResult;

        $this->Users = $this->populateResultUserListType($data->Users);
        $this->IsTruncated = ($v = $data->IsTruncated) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->Marker = ($v = $data->Marker) ? (string) $v : null;
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Tag([
                'Key' => (string) $item->Key,
                'Value' => (string) $item->Value,
            ]);
        }

        return $items;
    }

    /**
     * @return User[]
     */
    private function populateResultUserListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new User([
                'Path' => (string) $item->Path,
                'UserName' => (string) $item->UserName,
                'UserId' => (string) $item->UserId,
                'Arn' => (string) $item->Arn,
                'CreateDate' => new \DateTimeImmutable((string) $item->CreateDate),
                'PasswordLastUsed' => ($v = $item->PasswordLastUsed) ? new \DateTimeImmutable((string) $v) : null,
                'PermissionsBoundary' => !$item->PermissionsBoundary ? null : new AttachedPermissionsBoundary([
                    'PermissionsBoundaryType' => ($v = $item->PermissionsBoundary->PermissionsBoundaryType) ? (string) $v : null,
                    'PermissionsBoundaryArn' => ($v = $item->PermissionsBoundary->PermissionsBoundaryArn) ? (string) $v : null,
                ]),
                'Tags' => !$item->Tags ? [] : $this->populateResultTagListType($item->Tags),
            ]);
        }

        return $items;
    }
}
