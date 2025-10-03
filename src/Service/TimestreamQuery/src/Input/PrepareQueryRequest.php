<?php

namespace AsyncAws\TimestreamQuery\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PrepareQueryRequest extends Input
{
    /**
     * The Timestream query string that you want to use as a prepared statement. Parameter names can be specified in the
     * query string `@` character followed by an identifier.
     *
     * @required
     *
     * @var string|null
     */
    private $queryString;

    /**
     * By setting this value to `true`, Timestream will only validate that the query string is a valid Timestream query, and
     * not store the prepared query for later use.
     *
     * @var bool|null
     */
    private $validateOnly;

    /**
     * @param array{
     *   QueryString?: string,
     *   ValidateOnly?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryString = $input['QueryString'] ?? null;
        $this->validateOnly = $input['ValidateOnly'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueryString?: string,
     *   ValidateOnly?: bool|null,
     *   '@region'?: string|null,
     * }|PrepareQueryRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function getValidateOnly(): ?bool
    {
        return $this->validateOnly;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'Timestream_20181101.PrepareQuery',
            'Accept' => 'application/json',
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

    public function setQueryString(?string $value): self
    {
        $this->queryString = $value;

        return $this;
    }

    public function setValidateOnly(?bool $value): self
    {
        $this->validateOnly = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queryString) {
            throw new InvalidArgument(\sprintf('Missing parameter "QueryString" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueryString'] = $v;
        if (null !== $v = $this->validateOnly) {
            $payload['ValidateOnly'] = (bool) $v;
        }

        return $payload;
    }
}
