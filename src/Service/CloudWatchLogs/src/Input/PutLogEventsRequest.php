<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutLogEventsRequest extends Input
{
    /**
     * The name of the log group.
     *
     * @required
     *
     * @var string|null
     */
    private $logGroupName;

    /**
     * The name of the log stream.
     *
     * @required
     *
     * @var string|null
     */
    private $logStreamName;

    /**
     * The log events.
     *
     * @required
     *
     * @var InputLogEvent[]
     */
    private $logEvents;

    /**
     * The sequence token obtained from the response of the previous `PutLogEvents` call. An upload in a newly created log
     * stream does not require a sequence token. You can also get the sequence token using DescribeLogStreams. If you call
     * `PutLogEvents` twice within a narrow time period using the same value for `sequenceToken`, both calls may be
     * successful, or one may be rejected.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogStreams.html
     *
     * @var string|null
     */
    private $sequenceToken;

    /**
     * @param array{
     *   logGroupName?: string,
     *   logStreamName?: string,
     *   logEvents?: \AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent[],
     *   sequenceToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->logStreamName = $input['logStreamName'] ?? null;
        $this->logEvents = array_map([InputLogEvent::class, 'create'], $input['logEvents'] ?? []);
        $this->sequenceToken = $input['sequenceToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InputLogEvent[]
     */
    public function getLogEvents(): array
    {
        return $this->logEvents;
    }

    public function getLogGroupName(): ?string
    {
        return $this->logGroupName;
    }

    public function getLogStreamName(): ?string
    {
        return $this->logStreamName;
    }

    public function getSequenceToken(): ?string
    {
        return $this->sequenceToken;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Logs_20140328.PutLogEvents',
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
     * @param InputLogEvent[] $value
     */
    public function setLogEvents(array $value): self
    {
        $this->logEvents = $value;

        return $this;
    }

    public function setLogGroupName(?string $value): self
    {
        $this->logGroupName = $value;

        return $this;
    }

    public function setLogStreamName(?string $value): self
    {
        $this->logStreamName = $value;

        return $this;
    }

    public function setSequenceToken(?string $value): self
    {
        $this->sequenceToken = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->logGroupName) {
            throw new InvalidArgument(sprintf('Missing parameter "logGroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['logGroupName'] = $v;
        if (null === $v = $this->logStreamName) {
            throw new InvalidArgument(sprintf('Missing parameter "logStreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['logStreamName'] = $v;

        $index = -1;
        foreach ($this->logEvents as $listValue) {
            ++$index;
            $payload['logEvents'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->sequenceToken) {
            $payload['sequenceToken'] = $v;
        }

        return $payload;
    }
}
