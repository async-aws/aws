<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\AwsError\AwsError;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The sequence token is not valid. You can get the correct sequence token in the `expectedSequenceToken` field in the
 * `InvalidSequenceTokenException` message.
 */
final class InvalidSequenceTokenException extends ClientException
{
    private $expectedSequenceToken;

    public function __construct(ResponseInterface $response, ?AwsError $awsError)
    {
        parent::__construct($response, $awsError);
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
