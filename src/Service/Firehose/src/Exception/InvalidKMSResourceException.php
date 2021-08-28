<?php

namespace AsyncAws\Firehose\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Kinesis Data Firehose throws this exception when an attempt to put records or to start or stop delivery stream
 * encryption fails. This happens when the KMS service throws one of the following exception types:
 * `AccessDeniedException`, `InvalidStateException`, `DisabledException`, or `NotFoundException`.
 */
final class InvalidKMSResourceException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['code']) ? (string) $data['code'] : null)) {
            $this->code = $v;
        }
        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
