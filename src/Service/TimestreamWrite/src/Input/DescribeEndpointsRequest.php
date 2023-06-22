<?php

namespace AsyncAws\TimestreamWrite\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeEndpointsRequest extends Input
{
    /**
     * @param array{
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input);
    }

    /**
     * @param array{
     *   '@region'?: string|null,
     * }|DescribeEndpointsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'Timestream_20181101.DescribeEndpoints',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    private function requestBody(): array
    {
        $payload = [];

        return $payload;
    }
}
