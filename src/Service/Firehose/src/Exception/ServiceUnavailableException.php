<?php

namespace AsyncAws\Firehose\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The service is unavailable. Back off and retry the operation. If you continue to see the exception, throughput limits
 * for the delivery stream may have been exceeded. For more information about limits and how to request an increase, see
 * Amazon Kinesis Data Firehose Limits.
 *
 * @see https://docs.aws.amazon.com/firehose/latest/dev/limits.html
 */
final class ServiceUnavailableException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
