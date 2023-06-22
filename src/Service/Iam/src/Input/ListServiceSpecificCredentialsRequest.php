<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListServiceSpecificCredentialsRequest extends Input
{
    /**
     * The name of the user whose service-specific credentials you want information about. If this value is not specified,
     * then the operation assumes the user whose credentials are used to call the operation.
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
     * Filters the returned results to only those for the specified Amazon Web Services service. If not specified, then
     * Amazon Web Services returns service-specific credentials for all services.
     *
     * @var string|null
     */
    private $serviceName;

    /**
     * @param array{
     *   UserName?: string,
     *   ServiceName?: string,
     *   '@region'?: string|null,
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
        $body = http_build_query(['Action' => 'ListServiceSpecificCredentials', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

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
        if (null !== $v = $this->userName) {
            $payload['UserName'] = $v;
        }
        if (null !== $v = $this->serviceName) {
            $payload['ServiceName'] = $v;
        }

        return $payload;
    }
}
