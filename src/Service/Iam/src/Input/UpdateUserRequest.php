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
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of upper and lowercase
     * alphanumeric characters with no spaces. You can also include any of the following characters: _+=,.@-
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * New path for the IAM user. Include this parameter only if you're changing the user's path.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of either a forward slash
     * (/) by itself or a string that must begin and end with forward slashes. In addition, it can contain any ASCII
     * character from the ! (`\u0021`) through the DEL character (`\u007F`), including most punctuation characters, digits,
     * and upper and lowercased letters.
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @var string|null
     */
    private $newPath;

    /**
     * New name for the user. Include this parameter only if you're changing the user's name.
     *
     * IAM user, group, role, and policy names must be unique within the account. Names are not distinguished by case. For
     * example, you cannot create resources named both "MyResource" and "myresource".
     *
     * @var string|null
     */
    private $newUserName;

    /**
     * @param array{
     *   UserName?: string,
     *   NewPath?: string|null,
     *   NewUserName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->newPath = $input['NewPath'] ?? null;
        $this->newUserName = $input['NewUserName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserName?: string,
     *   NewPath?: string|null,
     *   NewUserName?: string|null,
     *   '@region'?: string|null,
     * }|UpdateUserRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNewPath(): ?string
    {
        return $this->newPath;
    }

    public function getNewUserName(): ?string
    {
        return $this->newUserName;
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
        $body = http_build_query(['Action' => 'UpdateUser', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setNewPath(?string $value): self
    {
        $this->newPath = $value;

        return $this;
    }

    public function setNewUserName(?string $value): self
    {
        $this->newUserName = $value;

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
            throw new InvalidArgument(\sprintf('Missing parameter "UserName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserName'] = $v;
        if (null !== $v = $this->newPath) {
            $payload['NewPath'] = $v;
        }
        if (null !== $v = $this->newUserName) {
            $payload['NewUserName'] = $v;
        }

        return $payload;
    }
}
