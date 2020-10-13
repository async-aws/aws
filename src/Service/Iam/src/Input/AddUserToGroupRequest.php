<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AddUserToGroupRequest extends Input
{
    /**
     * The name of the group to update.
     *
     * @required
     *
     * @var string|null
     */
    private $GroupName;

    /**
     * The name of the user to add.
     *
     * @required
     *
     * @var string|null
     */
    private $UserName;

    /**
     * @param array{
     *   GroupName?: string,
     *   UserName?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->GroupName = $input['GroupName'] ?? null;
        $this->UserName = $input['UserName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGroupName(): ?string
    {
        return $this->GroupName;
    }

    public function getUserName(): ?string
    {
        return $this->UserName;
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
        $body = http_build_query(['Action' => 'AddUserToGroup', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setGroupName(?string $value): self
    {
        $this->GroupName = $value;

        return $this;
    }

    public function setUserName(?string $value): self
    {
        $this->UserName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->GroupName) {
            throw new InvalidArgument(sprintf('Missing parameter "GroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['GroupName'] = $v;
        if (null === $v = $this->UserName) {
            throw new InvalidArgument(sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;

        return $payload;
    }
}
