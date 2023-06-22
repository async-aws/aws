<?php

namespace AsyncAws\MediaConvert\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\MediaConvert\Enum\DescribeEndpointsMode;

/**
 * Send an request with an empty body to the regional API endpoint to get your account API endpoint.
 */
final class DescribeEndpointsRequest extends Input
{
    /**
     * Optional. Max number of endpoints, up to twenty, that will be returned at one time.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * Optional field, defaults to DEFAULT. Specify DEFAULT for this operation to return your endpoints if any exist, or to
     * create an endpoint for you and return it if one doesn't already exist. Specify GET_ONLY to return your endpoints if
     * any exist, or an empty list if none exist.
     *
     * @var DescribeEndpointsMode::*|null
     */
    private $mode;

    /**
     * Use this string, provided with the response to a previous request, to request the next batch of endpoints.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   MaxResults?: int,
     *   Mode?: DescribeEndpointsMode::*,
     *   NextToken?: string,
     *   '@region'?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->mode = $input['Mode'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    /**
     * @return DescribeEndpointsMode::*|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/2017-08-29/endpoints';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    /**
     * @param DescribeEndpointsMode::*|null $value
     */
    public function setMode(?string $value): self
    {
        $this->mode = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maxResults) {
            $payload['maxResults'] = $v;
        }
        if (null !== $v = $this->mode) {
            if (!DescribeEndpointsMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "mode" for "%s". The value "%s" is not a valid "DescribeEndpointsMode".', __CLASS__, $v));
            }
            $payload['mode'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }

        return $payload;
    }
}
