<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteAccessKeyRequest extends Input
{
    /**
     * The name of the user whose access key pair you want to delete.
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
     * The access key ID for the access key ID and secret access key you want to delete.
     *
     * This parameter allows (through its regex pattern [^1]) a string of characters that can consist of any upper or
     * lowercased letter or digit.
     *
     * [^1]: http://wikipedia.org/wiki/regex
     *
     * @required
     *
     * @var string|null
     */
    private $accessKeyId;

    /**
     * @param array{
     *   UserName?: string|null,
     *   AccessKeyId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->accessKeyId = $input['AccessKeyId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserName?: string|null,
     *   AccessKeyId?: string,
     *   '@region'?: string|null,
     * }|DeleteAccessKeyRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): ?string
    {
        return $this->accessKeyId;
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
        $body = http_build_query(['Action' => 'DeleteAccessKey', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccessKeyId(?string $value): self
    {
        $this->accessKeyId = $value;

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
        if (null !== $v = $this->userName) {
            $payload['UserName'] = $v;
        }
        if (null === $v = $this->accessKeyId) {
            throw new InvalidArgument(\sprintf('Missing parameter "AccessKeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AccessKeyId'] = $v;

        return $payload;
    }
}
