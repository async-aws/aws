<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListUsersRequest extends Input
{
    /**
     * The user pool ID for the user pool on which the search should be performed.
     *
     * @required
     *
     * @var string|null
     */
    private $UserPoolId;

    /**
     * An array of strings, where each string is the name of a user attribute to be returned for each user in the search
     * results. If the array is null, all attributes are returned.
     *
     * @var string[]
     */
    private $AttributesToGet;

    /**
     * Maximum number of users to be returned.
     *
     * @var int|null
     */
    private $Limit;

    /**
     * An identifier that was returned from the previous call to this operation, which can be used to return the next set of
     * items in the list.
     *
     * @var string|null
     */
    private $PaginationToken;

    /**
     * A filter string of the form "*AttributeName**Filter-Type* "*AttributeValue*"". Quotation marks within the filter
     * string must be escaped using the backslash (\) character. For example, "`family_name` = \"Reddy\"".
     *
     * @var string|null
     */
    private $Filter;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   PaginationToken?: string,
     *   Filter?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserPoolId = $input['UserPoolId'] ?? null;
        $this->AttributesToGet = $input['AttributesToGet'] ?? [];
        $this->Limit = $input['Limit'] ?? null;
        $this->PaginationToken = $input['PaginationToken'] ?? null;
        $this->Filter = $input['Filter'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributesToGet(): array
    {
        return $this->AttributesToGet;
    }

    public function getFilter(): ?string
    {
        return $this->Filter;
    }

    public function getLimit(): ?int
    {
        return $this->Limit;
    }

    public function getPaginationToken(): ?string
    {
        return $this->PaginationToken;
    }

    public function getUserPoolId(): ?string
    {
        return $this->UserPoolId;
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
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param string[] $value
     */
    public function setAttributesToGet(array $value): self
    {
        $this->AttributesToGet = $value;

        return $this;
    }

    public function setFilter(?string $value): self
    {
        $this->Filter = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->Limit = $value;

        return $this;
    }

    public function setPaginationToken(?string $value): self
    {
        $this->PaginationToken = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->UserPoolId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->UserPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;

        $index = -1;
        foreach ($this->AttributesToGet as $listValue) {
            ++$index;
            $payload['AttributesToGet'][$index] = $listValue;
        }

        if (null !== $v = $this->Limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->PaginationToken) {
            $payload['PaginationToken'] = $v;
        }
        if (null !== $v = $this->Filter) {
            $payload['Filter'] = $v;
        }

        return $payload;
    }
}
