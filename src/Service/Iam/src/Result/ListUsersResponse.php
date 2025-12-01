<?php

namespace AsyncAws\Iam\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Iam\Enum\PermissionsBoundaryAttachmentType;
use AsyncAws\Iam\IamClient;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\ValueObject\AttachedPermissionsBoundary;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

/**
 * Contains the response to a successful ListUsers [^1] request.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListUsers.html
 *
 * @implements \IteratorAggregate<User>
 */
class ListUsersResponse extends Result implements \IteratorAggregate
{
    /**
     * A list of users.
     *
     * @var User[]
     */
    private $users;

    /**
     * A flag that indicates whether there are more items to return. If your results were truncated, you can make a
     * subsequent pagination request using the `Marker` request parameter to retrieve more items. Note that IAM might return
     * fewer than the `MaxItems` number of results even when there are more results available. We recommend that you check
     * `IsTruncated` after every call to ensure that you receive all your results.
     *
     * @var bool|null
     */
    private $isTruncated;

    /**
     * When `IsTruncated` is `true`, this element is present and contains the value to use for the `Marker` parameter in a
     * subsequent pagination request.
     *
     * @var string|null
     */
    private $marker;

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    /**
     * Iterates over Users.
     *
     * @return \Traversable<User>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getUsers();
    }

    public function getMarker(): ?string
    {
        $this->initialize();

        return $this->marker;
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
            yield from $this->users;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof IamClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListUsersRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setMarker($page->marker);

                $this->registerPrefetch($nextPage = $client->listUsers($input));
            } else {
                $nextPage = null;
            }

            yield from $page->users;

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

        $this->users = $this->populateResultUserListType($data->Users);
        $this->isTruncated = (null !== $v = $data->IsTruncated[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->marker = (null !== $v = $data->Marker[0]) ? (string) $v : null;
    }

    private function populateResultAttachedPermissionsBoundary(\SimpleXMLElement $xml): AttachedPermissionsBoundary
    {
        return new AttachedPermissionsBoundary([
            'PermissionsBoundaryType' => (null !== $v = $xml->PermissionsBoundaryType[0]) ? (!PermissionsBoundaryAttachmentType::exists((string) $xml->PermissionsBoundaryType) ? PermissionsBoundaryAttachmentType::UNKNOWN_TO_SDK : (string) $xml->PermissionsBoundaryType) : null,
            'PermissionsBoundaryArn' => (null !== $v = $xml->PermissionsBoundaryArn[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultTag(\SimpleXMLElement $xml): Tag
    {
        return new Tag([
            'Key' => (string) $xml->Key,
            'Value' => (string) $xml->Value,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }

    private function populateResultUser(\SimpleXMLElement $xml): User
    {
        return new User([
            'Path' => (string) $xml->Path,
            'UserName' => (string) $xml->UserName,
            'UserId' => (string) $xml->UserId,
            'Arn' => (string) $xml->Arn,
            'CreateDate' => new \DateTimeImmutable((string) $xml->CreateDate),
            'PasswordLastUsed' => (null !== $v = $xml->PasswordLastUsed[0]) ? new \DateTimeImmutable((string) $v) : null,
            'PermissionsBoundary' => 0 === $xml->PermissionsBoundary->count() ? null : $this->populateResultAttachedPermissionsBoundary($xml->PermissionsBoundary),
            'Tags' => (0 === ($v = $xml->Tags)->count()) ? null : $this->populateResultTagListType($v),
        ]);
    }

    /**
     * @return User[]
     */
    private function populateResultUserListType(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultUser($item);
        }

        return $items;
    }
}
