<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class DataAlreadyAcceptedException extends ClientException
{
    private $expectedSequenceToken;

    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->populateResult($response);
    }

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
