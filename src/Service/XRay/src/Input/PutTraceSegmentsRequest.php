<?php

namespace AsyncAws\XRay\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutTraceSegmentsRequest extends Input
{
    /**
     * A string containing a JSON document defining one or more segments or subsegments.
     *
     * @required
     *
     * @var string[]|null
     */
    private $traceSegmentDocuments;

    /**
     * @param array{
     *   TraceSegmentDocuments?: string[],
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->traceSegmentDocuments = $input['TraceSegmentDocuments'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TraceSegmentDocuments?: string[],
     *   '@region'?: string|null,
     * }|PutTraceSegmentsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getTraceSegmentDocuments(): array
    {
        return $this->traceSegmentDocuments ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/TraceSegments';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param string[] $value
     */
    public function setTraceSegmentDocuments(array $value): self
    {
        $this->traceSegmentDocuments = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->traceSegmentDocuments) {
            throw new InvalidArgument(sprintf('Missing parameter "TraceSegmentDocuments" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['TraceSegmentDocuments'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['TraceSegmentDocuments'][$index] = $listValue;
        }

        return $payload;
    }
}
