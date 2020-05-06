<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\MFAOptionType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class ListUsersResponse extends Result implements \IteratorAggregate
{
    /**
     * The users returned in the request to list users.
     */
    private $Users = [];

    /**
     * An identifier that was returned from the previous call to this operation, which can be used to return the next set of
     * items in the list.
     */
    private $PaginationToken;

    /**
     * Iterates over Users.
     *
     * @return \Traversable<UserType>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof CognitoIdentityProviderClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListUsersRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getPaginationToken()) {
                $input->setPaginationToken($page->getPaginationToken());

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

    public function getPaginationToken(): ?string
    {
        $this->initialize();

        return $this->PaginationToken;
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
            yield from $this->Users;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CognitoIdentityProviderClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListUsersRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getPaginationToken()) {
                $input->setPaginationToken($page->getPaginationToken());

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
        $data = $response->toArray();

        $this->Users = empty($data['Users']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new UserType([
                    'Username' => isset($item['Username']) ? (string) $item['Username'] : null,
                    'Attributes' => empty($item['Attributes']) ? [] : (function (array $json): array {
                        $items = [];
                        foreach ($json as $item) {
                            $items[] = new AttributeType([
                                'Name' => (string) $item['Name'],
                                'Value' => isset($item['Value']) ? (string) $item['Value'] : null,
                            ]);
                        }

                        return $items;
                    })($item['Attributes']),
                    'UserCreateDate' => (isset($item['UserCreateDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item['UserCreateDate'])))) ? $d : null,
                    'UserLastModifiedDate' => (isset($item['UserLastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item['UserLastModifiedDate'])))) ? $d : null,
                    'Enabled' => isset($item['Enabled']) ? filter_var($item['Enabled'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'UserStatus' => isset($item['UserStatus']) ? (string) $item['UserStatus'] : null,
                    'MFAOptions' => empty($item['MFAOptions']) ? [] : (function (array $json): array {
                        $items = [];
                        foreach ($json as $item) {
                            $items[] = new MFAOptionType([
                                'DeliveryMedium' => isset($item['DeliveryMedium']) ? (string) $item['DeliveryMedium'] : null,
                                'AttributeName' => isset($item['AttributeName']) ? (string) $item['AttributeName'] : null,
                            ]);
                        }

                        return $items;
                    })($item['MFAOptions']),
                ]);
            }

            return $items;
        })($data['Users']);
        $this->PaginationToken = isset($data['PaginationToken']) ? (string) $data['PaginationToken'] : null;
    }
}
