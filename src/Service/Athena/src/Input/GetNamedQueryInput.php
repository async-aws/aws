<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetNamedQueryInput extends Input
{
    /**
     * The unique ID of the query. Use ListNamedQueries to get query IDs.
     *
     * @required
     *
     * @var string|null
     */
    private $namedQueryId;

    /**
     * @param array{
     *   NamedQueryId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->namedQueryId = $input['NamedQueryId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   NamedQueryId?: string,
     *   '@region'?: string|null,
     * }|GetNamedQueryInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNamedQueryId(): ?string
    {
        return $this->namedQueryId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonAthena.GetNamedQuery',
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

    public function setNamedQueryId(?string $value): self
    {
        $this->namedQueryId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->namedQueryId) {
            throw new InvalidArgument(\sprintf('Missing parameter "NamedQueryId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['NamedQueryId'] = $v;

        return $payload;
    }
}
