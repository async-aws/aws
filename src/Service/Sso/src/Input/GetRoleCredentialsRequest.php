<?php

namespace AsyncAws\Sso\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetRoleCredentialsRequest extends Input
{
    /**
     * The friendly name of the role that is assigned to the user.
     *
     * @required
     *
     * @var string|null
     */
    private $roleName;

    /**
     * The identifier for the AWS account that is assigned to the user.
     *
     * @required
     *
     * @var string|null
     */
    private $accountId;

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
     *   roleName?: string,
     *   accountId?: string,
     *   accessToken?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->roleName = $input['roleName'] ?? null;
        $this->accountId = $input['accountId'] ?? null;
        $this->accessToken = $input['accessToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   roleName?: string,
     *   accountId?: string,
     *   accessToken?: string,
     *   '@region'?: string|null,
     * }|GetRoleCredentialsRequest $input
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

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];
        if (null === $v = $this->accessToken) {
            throw new InvalidArgument(sprintf('Missing parameter "accessToken" for "%s". The value cannot be null.', __CLASS__));
        }
        $headers['x-amz-sso_bearer_token'] = $v;

        // Prepare query
        $query = [];
        if (null === $v = $this->roleName) {
            throw new InvalidArgument(sprintf('Missing parameter "roleName" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['role_name'] = $v;
        if (null === $v = $this->accountId) {
            throw new InvalidArgument(sprintf('Missing parameter "accountId" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['account_id'] = $v;

        // Prepare URI
        $uriString = '/federation/credentials';

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

    public function setRoleName(?string $value): self
    {
        $this->roleName = $value;

        return $this;
    }
}
