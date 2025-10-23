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
     * A flag indicating whether to list service specific credentials for all users. This parameter cannot be specified
     * together with UserName. When true, returns all credentials associated with the specified service.
     *
     * @var bool|null
     */
    private $allUsers;

    /**
     * Use this parameter only when paginating results and only after you receive a response indicating that the results are
     * truncated. Set it to the value of the Marker from the response that you received to indicate where the next call
     * should start.
     *
     * @var string|null
     */
    private $marker;

    /**
     * Use this only when paginating results to indicate the maximum number of items you want in the response. If additional
     * items exist beyond the maximum you specify, the IsTruncated response element is true.
     *
     * @var int|null
     */
    private $maxItems;

    /**
     * @param array{
     *   UserName?: string|null,
     *   ServiceName?: string|null,
     *   AllUsers?: bool|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userName = $input['UserName'] ?? null;
        $this->serviceName = $input['ServiceName'] ?? null;
        $this->allUsers = $input['AllUsers'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserName?: string|null,
     *   ServiceName?: string|null,
     *   AllUsers?: bool|null,
     *   Marker?: string|null,
     *   MaxItems?: int|null,
     *   '@region'?: string|null,
     * }|ListServiceSpecificCredentialsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAllUsers(): ?bool
    {
        return $this->allUsers;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
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

    public function setAllUsers(?bool $value): self
    {
        $this->allUsers = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->maxItems = $value;

        return $this;
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
        if (null !== $v = $this->allUsers) {
            $payload['AllUsers'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->marker) {
            $payload['Marker'] = $v;
        }
        if (null !== $v = $this->maxItems) {
            $payload['MaxItems'] = $v;
        }

        return $payload;
    }
}
