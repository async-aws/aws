<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateLogStreamRequest extends Input
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
     * @param array{
     *   logGroupName?: string,
     *   logStreamName?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->logStreamName = $input['logStreamName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLogGroupName(): ?string
    {
        return $this->logGroupName;
    }

    public function getLogStreamName(): ?string
    {
        return $this->logStreamName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Logs_20140328.CreateLogStream',
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

        return $payload;
    }
}
