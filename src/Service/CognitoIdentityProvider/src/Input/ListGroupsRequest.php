<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListGroupsRequest extends Input
{
    /**
     * The ID of the user pool where you want to list user groups.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The maximum number of groups that you want Amazon Cognito to return in the response.
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
    private $nextToken;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Limit?: null|int,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Limit?: null|int,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|ListGroupsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
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
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.ListGroups',
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

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

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
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
