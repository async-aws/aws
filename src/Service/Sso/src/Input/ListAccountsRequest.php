<?php

namespace AsyncAws\Sso\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListAccountsRequest extends Input
{
    /**
     * (Optional) When requesting subsequent pages, this is the page token from the previous response output.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * This is the number of items clients can request per page.
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
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   accessToken?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->accessToken = $input['accessToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   accessToken?: string,
     *   '@region'?: string|null,
     * }|ListAccountsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
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

        // Prepare URI
        $uriString = '/assignment/accounts';

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
