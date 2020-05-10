<?php

namespace AsyncAws\Iam\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListUsersRequest extends Input
{
    /**
     * The path prefix for filtering the results. For example: `/division_abc/subdivision_xyz/`, which would get all user
     * names whose path starts with `/division_abc/subdivision_xyz/`.
     *
     * @var string|null
     */
    private $PathPrefix;

    /**
     * Use this parameter only when paginating results and only after you receive a response indicating that the results are
     * truncated. Set it to the value of the `Marker` element in the response that you received to indicate where the next
     * call should start.
     *
     * @var string|null
     */
    private $Marker;

    /**
     * Use this only when paginating results to indicate the maximum number of items you want in the response. If additional
     * items exist beyond the maximum you specify, the `IsTruncated` response element is `true`.
     *
     * @var int|null
     */
    private $MaxItems;

    /**
     * @param array{
     *   PathPrefix?: string,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->PathPrefix = $input['PathPrefix'] ?? null;
        $this->Marker = $input['Marker'] ?? null;
        $this->MaxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMarker(): ?string
    {
        return $this->Marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->MaxItems;
    }

    public function getPathPrefix(): ?string
    {
        return $this->PathPrefix;
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
        $body = http_build_query(['Action' => 'ListUsers', 'Version' => '2010-05-08'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMarker(?string $value): self
    {
        $this->Marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->MaxItems = $value;

        return $this;
    }

    public function setPathPrefix(?string $value): self
    {
        $this->PathPrefix = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->PathPrefix) {
            $payload['PathPrefix'] = $v;
        }
        if (null !== $v = $this->Marker) {
            $payload['Marker'] = $v;
        }
        if (null !== $v = $this->MaxItems) {
            $payload['MaxItems'] = $v;
        }

        return $payload;
    }
}
