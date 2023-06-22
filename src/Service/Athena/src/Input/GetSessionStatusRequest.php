<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetSessionStatusRequest extends Input
{
    /**
     * The session ID.
     *
     * @required
     *
     * @var string|null
     */
    private $sessionId;

    /**
     * @param array{
     *   SessionId?: string,
     *   '@region'?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->sessionId = $input['SessionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonAthena.GetSessionStatus',
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

    public function setSessionId(?string $value): self
    {
        $this->sessionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->sessionId) {
            throw new InvalidArgument(sprintf('Missing parameter "SessionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SessionId'] = $v;

        return $payload;
    }
}
