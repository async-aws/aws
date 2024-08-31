<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\CloudWatchLogs\ValueObject\Entity;
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
     * @var InputLogEvent[]|null
     */
    private $logEvents;

    /**
     * The sequence token obtained from the response of the previous `PutLogEvents` call.
     *
     * ! The `sequenceToken` parameter is now ignored in `PutLogEvents` actions. `PutLogEvents` actions are now accepted and
     * ! never return `InvalidSequenceTokenException` or `DataAlreadyAcceptedException` even if the sequence token is not
     * ! valid.
     *
     * @var string|null
     */
    private $sequenceToken;

    /**
     * Reserved for future use.
     *
     * @var Entity|null
     */
    private $entity;

    /**
     * @param array{
     *   logGroupName?: string,
     *   logStreamName?: string,
     *   logEvents?: array<InputLogEvent|array>,
     *   sequenceToken?: null|string,
     *   entity?: null|Entity|array,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->logStreamName = $input['logStreamName'] ?? null;
        $this->logEvents = isset($input['logEvents']) ? array_map([InputLogEvent::class, 'create'], $input['logEvents']) : null;
        $this->sequenceToken = $input['sequenceToken'] ?? null;
        $this->entity = isset($input['entity']) ? Entity::create($input['entity']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   logGroupName?: string,
     *   logStreamName?: string,
     *   logEvents?: array<InputLogEvent|array>,
     *   sequenceToken?: null|string,
     *   entity?: null|Entity|array,
     *   '@region'?: string|null,
     * }|PutLogEventsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    /**
     * @return InputLogEvent[]
     */
    public function getLogEvents(): array
    {
        return $this->logEvents ?? [];
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

    public function setEntity(?Entity $value): self
    {
        $this->entity = $value;

        return $this;
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
            throw new InvalidArgument(\sprintf('Missing parameter "logGroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['logGroupName'] = $v;
        if (null === $v = $this->logStreamName) {
            throw new InvalidArgument(\sprintf('Missing parameter "logStreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['logStreamName'] = $v;
        if (null === $v = $this->logEvents) {
            throw new InvalidArgument(\sprintf('Missing parameter "logEvents" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['logEvents'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['logEvents'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->sequenceToken) {
            $payload['sequenceToken'] = $v;
        }
        if (null !== $v = $this->entity) {
            $payload['entity'] = $v->requestBody();
        }

        return $payload;
    }
}
