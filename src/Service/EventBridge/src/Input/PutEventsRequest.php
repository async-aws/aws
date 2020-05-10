<?php

namespace AsyncAws\EventBridge\Input;

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
     * @var PutEventsRequestEntry[]
     */
    private $Entries;

    /**
     * @param array{
     *   Entries?: \AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Entries = array_map([PutEventsRequestEntry::class, 'create'], $input['Entries'] ?? []);
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return PutEventsRequestEntry[]
     */
    public function getEntries(): array
    {
        return $this->Entries;
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
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param PutEventsRequestEntry[] $value
     */
    public function setEntries(array $value): self
    {
        $this->Entries = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        $index = -1;
        foreach ($this->Entries as $listValue) {
            ++$index;
            $payload['Entries'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
