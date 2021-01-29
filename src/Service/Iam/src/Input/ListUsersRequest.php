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
    private $pathPrefix;

    /**
     * Use this parameter only when paginating results and only after you receive a response indicating that the results are
     * truncated. Set it to the value of the `Marker` element in the response that you received to indicate where the next
     * call should start.
     *
     * @var string|null
     */
    private $marker;

    /**
     * Use this only when paginating results to indicate the maximum number of items you want in the response. If additional
     * items exist beyond the maximum you specify, the `IsTruncated` response element is `true`.
     *
     * @var int|null
     */
    private $maxItems;

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
        $this->pathPrefix = $input['PathPrefix'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->maxItems = $input['MaxItems'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }

    public function getPathPrefix(): ?string
    {
        return $this->pathPrefix;
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
        $this->marker = $value;

        return $this;
    }

    public function setMaxItems(?int $value): self
    {
        $this->maxItems = $value;

        return $this;
    }

    public function setPathPrefix(?string $value): self
    {
        $this->pathPrefix = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->pathPrefix) {
            $payload['PathPrefix'] = $v;
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
