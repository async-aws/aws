<?php

namespace AsyncAws\Sso\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListAccountRolesRequest extends Input
{
    /**
     * The page token from the previous response output when you request subsequent pages.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The number of items that clients can request per page.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The token issued by the `CreateToken` API call. For more information, see CreateToken [^1] in the *IAM Identity
     * Center OIDC API Reference Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/singlesignon/latest/OIDCAPIReference/API_CreateToken.html
     *
     * @required
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * The identifier for the AWS account that is assigned to the user.
     *
     * @required
     *
     * @var string|null
     */
    private $accountId;

    /**
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   accessToken?: string,
     *   accountId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->accessToken = $input['accessToken'] ?? null;
        $this->accountId = $input['accountId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   accessToken?: string,
     *   accountId?: string,
     *   '@region'?: string|null,
     * }|ListAccountRolesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        if (null === $v = $this->accessToken) {
            throw new InvalidArgument(\sprintf('Missing parameter "accessToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $headers['x-amz-sso_bearer_token'] = $v;

        // Prepare query
        $query = [];
        if (null !== $this->nextToken) {
            $query['next_token'] = $this->nextToken;
        }
        if (null !== $this->maxResults) {
            $query['max_result'] = (string) $this->maxResults;
        }
        if (null === $v = $this->accountId) {
            throw new InvalidArgument(\sprintf('Missing parameter "accountId" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['account_id'] = $v;

        // Prepare URI
        $uriString = '/assignment/roles';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccessToken(?string $value): self
    {
        $this->accessToken = $value;

        return $this;
    }

    public function setAccountId(?string $value): self
    {
        $this->accountId = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }
}
