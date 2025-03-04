<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response from the request to list users.
 *
 * @implements \IteratorAggregate<UserType>
 */
class ListUsersResponse extends Result implements \IteratorAggregate
{
    /**
     * An array of user pool users who match your query, and their attributes.
     *
     * @var UserType[]
     */
    private $users;

    /**
     * The identifier that Amazon Cognito returned with the previous request to this operation. When you include a
     * pagination token in your request, Amazon Cognito returns the next set of items in the list. By use of this token, you
     * can paginate through the full list of items.
     *
     * @var string|null
     */
    private $paginationToken;

    /**
     * Iterates over Users.
     *
     * @return \Traversable<UserType>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getUsers();
    }

    public function getPaginationToken(): ?string
    {
        $this->initialize();

        return $this->paginationToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<UserType>
     */
    public function getUsers(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->users;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CognitoIdentityProviderClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListUsersRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->paginationToken) {
                $input->setPaginationToken($page->paginationToken);

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
        $data = $response->toArray();

        $this->users = empty($data['Users']) ? [] : $this->populateResultUsersListType($data['Users']);
        $this->paginationToken = isset($data['PaginationToken']) ? (string) $data['PaginationToken'] : null;
    }

    /**
     * @return AttributeType[]
     */
    private function populateResultAttributeListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultAttributeType($item);
        }

        return $items;
    }

    private function populateResultAttributeType(array $json): AttributeType
    {
        return new AttributeType([
            'Name' => (string) $json['Name'],
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
        ]);
    }

    /**
     * @return MFAOptionType[]
     */
    private function populateResultMFAOptionListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMFAOptionType($item);
        }

        return $items;
    }

    private function populateResultMFAOptionType(array $json): MFAOptionType
    {
        return new MFAOptionType([
            'DeliveryMedium' => isset($json['DeliveryMedium']) ? (string) $json['DeliveryMedium'] : null,
            'AttributeName' => isset($json['AttributeName']) ? (string) $json['AttributeName'] : null,
        ]);
    }

    private function populateResultUserType(array $json): UserType
    {
        return new UserType([
            'Username' => isset($json['Username']) ? (string) $json['Username'] : null,
            'Attributes' => !isset($json['Attributes']) ? null : $this->populateResultAttributeListType($json['Attributes']),
            'UserCreateDate' => (isset($json['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['UserCreateDate'])))) ? $d : null,
            'UserLastModifiedDate' => (isset($json['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['UserLastModifiedDate'])))) ? $d : null,
            'Enabled' => isset($json['Enabled']) ? filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'UserStatus' => isset($json['UserStatus']) ? (string) $json['UserStatus'] : null,
            'MFAOptions' => !isset($json['MFAOptions']) ? null : $this->populateResultMFAOptionListType($json['MFAOptions']),
        ]);
    }

    /**
     * @return UserType[]
     */
    private function populateResultUsersListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultUserType($item);
        }

        return $items;
    }
}
