<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutUserPolicyRequest extends Input
{
    /**
     * The name of the user to associate the policy with.
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The name of the policy document.
     *
     * @required
     *
     * @var string|null
     */
    private $policyName;

    /**
     * The policy document.
     *
     * @required
     *
     * @var string|null
     */
    private $policyDocument;

    /**
     * @param array{
     *   UserName?: string,
     *   PolicyName?: string,
     *   PolicyDocument?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->policyName = $input['PolicyName'] ?? null;
        $this->policyDocument = $input['PolicyDocument'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPolicyDocument(): ?string
    {
        return $this->policyDocument;
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
        $body = http_build_query(['Action' => 'PutUserPolicy', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPolicyDocument(?string $value): self
    {
        $this->policyDocument = $value;

        return $this;
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
        if (null === $v = $this->policyDocument) {
            throw new InvalidArgument(sprintf('Missing parameter "PolicyDocument" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['PolicyDocument'] = $v;

        return $payload;
    }
}
