<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateServiceSpecificCredentialRequest extends Input
{
    /**
     * The name of the IAM user that is to be associated with the credentials. The new service-specific credentials have the
     * same permissions as the associated user except that they can be used only to access the specified service.
     *
     * @required
     *
     * @var string|null
     */
    private $userName;

    /**
     * The name of the Amazon Web Services service that is to be associated with the credentials. The service you specify
     * here is the only service that can be accessed using these credentials.
     *
     * @required
     *
     * @var string|null
     */
    private $serviceName;

    /**
     * @param array{
     *   UserName?: string,
     *   ServiceName?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->serviceName = $input['ServiceName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
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
        $body = http_build_query(['Action' => 'CreateServiceSpecificCredential', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setServiceName(?string $value): self
    {
        $this->serviceName = $value;

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
        if (null === $v = $this->serviceName) {
            throw new InvalidArgument(sprintf('Missing parameter "ServiceName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ServiceName'] = $v;

        return $payload;
    }
}
