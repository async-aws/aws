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
     * @var string|null
     */
    private $UserName;

    /**
     * The access key ID for the access key ID and secret access key you want to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $AccessKeyId;

    /**
     * @param array{
     *   UserName?: string,
     *   AccessKeyId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserName = $input['UserName'] ?? null;
        $this->AccessKeyId = $input['AccessKeyId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): ?string
    {
        return $this->AccessKeyId;
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
        $body = http_build_query(['Action' => 'DeleteAccessKey', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccessKeyId(?string $value): self
    {
        $this->AccessKeyId = $value;

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
        if (null !== $v = $this->UserName) {
            $payload['UserName'] = $v;
        }
        if (null === $v = $this->AccessKeyId) {
            throw new InvalidArgument(sprintf('Missing parameter "AccessKeyId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AccessKeyId'] = $v;

        return $payload;
    }
}
