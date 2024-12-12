<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to list users.
 */
final class ListUsersRequest extends Input
{
    /**
     * The ID of the user pool on which the search should be performed.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * A JSON array of user attribute names, for example `given_name`, that you want Amazon Cognito to include in the
     * response for each user. When you don't provide an `AttributesToGet` parameter, Amazon Cognito returns all attributes
     * for each user.
     *
     * Use `AttributesToGet` with required attributes in your user pool, or in conjunction with `Filter`. Amazon Cognito
     * returns an error if not all users in the results have set a value for the attribute you request. Attributes that you
     * can't filter on, including custom attributes, must have a value set in every user profile before an `AttributesToGet`
     * parameter returns results.
     *
     * @var string[]|null
     */
    private $attributesToGet;

    /**
     * Maximum number of users to be returned.
     *
     * @var int|null
     */
    private $limit;

    /**
     * This API operation returns a limited number of results. The pagination token is an identifier that you can present in
     * an additional API request with the same parameters. When you include the pagination token, Amazon Cognito returns the
     * next set of items after the current list. Subsequent requests return a new pagination token. By use of this token,
     * you can paginate through the full list of items.
     *
     * @var string|null
     */
    private $paginationToken;

    /**
     * A filter string of the form `"AttributeName Filter-Type "AttributeValue"`. Quotation marks within the filter string
     * must be escaped using the backslash (`\`) character. For example, `"family_name = \"Reddy\""`.
     *
     * - *AttributeName*: The name of the attribute to search for. You can only search for one attribute at a time.
     * - *Filter-Type*: For an exact match, use `=`, for example, "`given_name = \"Jon\"`". For a prefix ("starts with")
     *   match, use `^=`, for example, "`given_name ^= \"Jon\"`".
     * - *AttributeValue*: The attribute value that must be matched for each user.
     *
     * If the filter string is empty, `ListUsers` returns all users in the user pool.
     *
     * You can only search for the following standard attributes:
     *
     * - `username` (case-sensitive)
     * - `email`
     * - `phone_number`
     * - `name`
     * - `given_name`
     * - `family_name`
     * - `preferred_username`
     * - `cognito:user_status` (called **Status** in the Console) (case-insensitive)
     * - `status (called **Enabled** in the Console) (case-sensitive)`
     * - `sub`
     *
     * Custom attributes aren't searchable.
     *
     * > You can also list users with a client-side filter. The server-side filter matches no more than one attribute. For
     * > an advanced search, use a client-side filter with the `--query` parameter of the `list-users` action in the CLI.
     * > When you use a client-side filter, ListUsers returns a paginated list of zero or more users. You can receive
     * > multiple pages in a row with zero results. Repeat the query with each pagination token that is returned until you
     * > receive a null pagination token value, and then review the combined result.
     * >
     * > For more information about server-side and client-side filtering, see FilteringCLI output [^1] in the Command Line
     * > Interface User Guide [^2].
     *
     * For more information, see Searching for Users Using the ListUsers API [^3] and Examples of Using the ListUsers API
     * [^4] in the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cli/latest/userguide/cli-usage-filter.html
     * [^2]: https://docs.aws.amazon.com/cli/latest/userguide/cli-usage-filter.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/how-to-manage-user-accounts.html#cognito-user-pools-searching-for-users-using-listusers-api
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/how-to-manage-user-accounts.html#cognito-user-pools-searching-for-users-listusers-api-examples
     *
     * @var string|null
     */
    private $filter;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   AttributesToGet?: null|string[],
     *   Limit?: null|int,
     *   PaginationToken?: null|string,
     *   Filter?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->attributesToGet = $input['AttributesToGet'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->paginationToken = $input['PaginationToken'] ?? null;
        $this->filter = $input['Filter'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   AttributesToGet?: null|string[],
     *   Limit?: null|int,
     *   PaginationToken?: null|string,
     *   Filter?: null|string,
     *   '@region'?: string|null,
     * }|ListUsersRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributesToGet(): array
    {
        return $this->attributesToGet ?? [];
    }

    public function getFilter(): ?string
    {
        return $this->filter;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getPaginationToken(): ?string
    {
        return $this->paginationToken;
    }

    public function getUserPoolId(): ?string
    {
        return $this->userPoolId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.ListUsers',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param string[] $value
     */
    public function setAttributesToGet(array $value): self
    {
        $this->attributesToGet = $value;

        return $this;
    }

    public function setFilter(?string $value): self
    {
        $this->filter = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setPaginationToken(?string $value): self
    {
        $this->paginationToken = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->userPoolId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userPoolId) {
            throw new InvalidArgument(\sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null !== $v = $this->attributesToGet) {
            $index = -1;
            $payload['AttributesToGet'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributesToGet'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->paginationToken) {
            $payload['PaginationToken'] = $v;
        }
        if (null !== $v = $this->filter) {
            $payload['Filter'] = $v;
        }

        return $payload;
    }
}
