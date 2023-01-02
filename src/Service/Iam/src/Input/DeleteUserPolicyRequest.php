<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteUserPolicyRequest extends Input
{
    /**
     * The name (friendly name, not ARN) identifying the user that the policy is embedded in.
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The name identifying the policy document to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $policyName;

    /**
     * @param array{
     *   UserName?: string,
     *   PolicyName?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->policyName = $input['PolicyName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPolicyName(): ?string
    {
        return $this->policyName;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'DeleteUserPolicy', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPolicyName(?string $value): self
    {
        $this->policyName = $value;

        return $this;
    }

    public function setUserName(?string $value): self
    {
        $this->userName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userName) {
            throw new InvalidArgument(sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;
        if (null === $v = $this->policyName) {
            throw new InvalidArgument(sprintf('Missing parameter "PolicyName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PolicyName'] = $v;

        return $payload;
    }
}
