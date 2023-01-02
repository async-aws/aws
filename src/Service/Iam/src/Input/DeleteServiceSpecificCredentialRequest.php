<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteServiceSpecificCredentialRequest extends Input
{
    /**
     * The name of the IAM user associated with the service-specific credential. If this value is not specified, then the
     * operation assumes the user whose credentials are used to call the operation.
     *
     * @var string|null
     */
    private $userName;

    /**
     * The unique identifier of the service-specific credential. You can get this value by calling
     * ListServiceSpecificCredentials.
     *
     * @required
     *
     * @var string|null
     */
    private $serviceSpecificCredentialId;

    /**
     * @param array{
     *   UserName?: string,
     *   ServiceSpecificCredentialId?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->serviceSpecificCredentialId = $input['ServiceSpecificCredentialId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getServiceSpecificCredentialId(): ?string
    {
        return $this->serviceSpecificCredentialId;
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
        $body = http_build_query(['Action' => 'DeleteServiceSpecificCredential', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setServiceSpecificCredentialId(?string $value): self
    {
        $this->serviceSpecificCredentialId = $value;

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
        if (null === $v = $this->serviceSpecificCredentialId) {
            throw new InvalidArgument(sprintf('Missing parameter "ServiceSpecificCredentialId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServiceSpecificCredentialId'] = $v;

        return $payload;
    }
}
