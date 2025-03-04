<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\ListGroupsRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\GroupType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<GroupType>
 */
class ListGroupsResponse extends Result implements \IteratorAggregate
{
    /**
     * An array of groups and their details. Each entry that's returned includes description, precedence, and IAM role
     * values.
     *
     * @var GroupType[]
     */
    private $groups;

    /**
     * The identifier that Amazon Cognito returned with the previous request to this operation. When you include a
     * pagination token in your request, Amazon Cognito returns the next set of items in the list. By use of this token, you
     * can paginate through the full list of items.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<GroupType>
     */
    public function getGroups(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->groups;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CognitoIdentityProviderClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListGroupsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listGroups($input));
            } else {
                $nextPage = null;
            }

            yield from $page->groups;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Groups.
     *
     * @return \Traversable<GroupType>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getGroups();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->groups = empty($data['Groups']) ? [] : $this->populateResultGroupListType($data['Groups']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    /**
     * @return GroupType[]
     */
    private function populateResultGroupListType(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultGroupType($item);
        }

        return $items;
    }

    private function populateResultGroupType(array $json): GroupType
    {
        return new GroupType([
            'GroupName' => isset($json['GroupName']) ? (string) $json['GroupName'] : null,
            'UserPoolId' => isset($json['UserPoolId']) ? (string) $json['UserPoolId'] : null,
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'RoleArn' => isset($json['RoleArn']) ? (string) $json['RoleArn'] : null,
            'Precedence' => isset($json['Precedence']) ? (int) $json['Precedence'] : null,
            'LastModifiedDate' => (isset($json['LastModifiedDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModifiedDate'])))) ? $d : null,
            'CreationDate' => (isset($json['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationDate'])))) ? $d : null,
        ]);
    }
}
