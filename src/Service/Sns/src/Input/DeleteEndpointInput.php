<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Input for `DeleteEndpoint` action.
 */
final class DeleteEndpointInput extends Input
{
    /**
     * `EndpointArn` of endpoint to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $endpointArn;

    /**
     * @param array{
     *   EndpointArn?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->endpointArn = $input['EndpointArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   EndpointArn?: string,
     *   '@region'?: string|null,
     * }|DeleteEndpointInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndpointArn(): ?string
    {
        return $this->endpointArn;
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
        $body = http_build_query(['Action' => 'DeleteEndpoint', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setEndpointArn(?string $value): self
    {
        $this->endpointArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->endpointArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "EndpointArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['EndpointArn'] = $v;

        return $payload;
    }
}
