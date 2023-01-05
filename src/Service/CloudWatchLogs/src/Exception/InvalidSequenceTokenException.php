<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The sequence token is not valid. You can get the correct sequence token in the `expectedSequenceToken` field in the
 * `InvalidSequenceTokenException` message.
 *
 * ! `PutLogEvents` actions are now always accepted and never return `InvalidSequenceTokenException` regardless of
 * ! receiving an invalid sequence token.
 */
final class InvalidSequenceTokenException extends ClientException
{
    private $expectedSequenceToken;

    public function getExpectedSequenceToken(): ?string
    {
        return $this->expectedSequenceToken;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->expectedSequenceToken = isset($data['expectedSequenceToken']) ? (string) $data['expectedSequenceToken'] : null;
    }
}
