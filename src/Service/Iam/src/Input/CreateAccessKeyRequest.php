<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateAccessKeyRequest extends Input
{
    /**
     * The name of the IAM user that the new key will belong to.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters consisting of upper and lowercase
     * alphanumeric characters with no spaces. You can also include any of the following characters: _+=,.@-
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @var string|null
     */
    private $userName;

    /**
     * @param array{
     *   UserName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserName?: string|null,
     *   '@region'?: string|null,
     * }|CreateAccessKeyRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
        $body = http_build_query(['Action' => 'CreateAccessKey', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setUserName(?string $value): self
    {
        $this->userName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->userName) {
            $payload['UserName'] = $v;
        }

        return $payload;
    }
}
