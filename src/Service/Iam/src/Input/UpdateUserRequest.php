<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateUserRequest extends Input
{
    /**
     * Name of the user to update. If you're changing the name of the user, this is the original user name.
     *
     * @required
     *
     * @var string|null
     */
    private $UserName;

    /**
     * New path for the IAM user. Include this parameter only if you're changing the user's path.
     *
     * @var string|null
     */
    private $NewPath;

    /**
     * New name for the user. Include this parameter only if you're changing the user's name.
     *
     * @var string|null
     */
    private $NewUserName;

    /**
     * @param array{
     *   UserName?: string,
     *   NewPath?: string,
     *   NewUserName?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserName = $input['UserName'] ?? null;
        $this->NewPath = $input['NewPath'] ?? null;
        $this->NewUserName = $input['NewUserName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNewPath(): ?string
    {
        return $this->NewPath;
    }

    public function getNewUserName(): ?string
    {
        return $this->NewUserName;
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
        $body = http_build_query(['Action' => 'UpdateUser', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setNewPath(?string $value): self
    {
        $this->NewPath = $value;

        return $this;
    }

    public function setNewUserName(?string $value): self
    {
        $this->NewUserName = $value;

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
        if (null === $v = $this->UserName) {
            throw new InvalidArgument(sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;
        if (null !== $v = $this->NewPath) {
            $payload['NewPath'] = $v;
        }
        if (null !== $v = $this->NewUserName) {
            $payload['NewUserName'] = $v;
        }

        return $payload;
    }
}
