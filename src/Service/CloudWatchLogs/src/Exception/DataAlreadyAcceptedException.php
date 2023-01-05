<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The event was already logged.
 *
 * ! `PutLogEvents` actions are now always accepted and never return `DataAlreadyAcceptedException` regardless of
 * ! whether a given batch of log events has already been accepted.
 */
final class DataAlreadyAcceptedException extends ClientException
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
