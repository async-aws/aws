<?php

namespace AsyncAws\EventBridge\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;

final class PutEventsRequest extends Input
{
    /**
     * The entry that defines an event in your system. You can specify several parameters for the entry such as the source
     * and type of the event, resources associated with the event, and so on.
     *
     * @required
     *
     * @var PutEventsRequestEntry[]|null
     */
    private $entries;

    /**
     * The URL subdomain of the endpoint. For example, if the URL for Endpoint is
     * https://abcde.veo.endpoints.event.amazonaws.com, then the EndpointId is `abcde.veo`.
     *
     * ! When using Java, you must include `auth-crt` on the class path.
     *
     * @var string|null
     */
    private $endpointId;

    /**
     * @param array{
     *   Entries?: array<PutEventsRequestEntry|array>,
     *   EndpointId?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->entries = isset($input['Entries']) ? array_map([PutEventsRequestEntry::class, 'create'], $input['Entries']) : null;
        $this->endpointId = $input['EndpointId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Entries?: array<PutEventsRequestEntry|array>,
     *   EndpointId?: string|null,
     *   '@region'?: string|null,
     * }|PutEventsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndpointId(): ?string
    {
        return $this->endpointId;
    }

    /**
     * @return PutEventsRequestEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSEvents.PutEvents',
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

    public function setEndpointId(?string $value): self
    {
        $this->endpointId = $value;

        return $this;
    }

    /**
     * @param PutEventsRequestEntry[] $value
     */
    public function setEntries(array $value): self
    {
        $this->entries = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->entries) {
            throw new InvalidArgument(\sprintf('Missing parameter "Entries" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Entries'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Entries'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->endpointId) {
            $payload['EndpointId'] = $v;
        }

        return $payload;
    }
}
