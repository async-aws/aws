<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request rate for the stream is too high, or the requested data is too large for the available throughput. Reduce
 * the frequency or size of your requests. For more information, see Streams Limits in the *Amazon Kinesis Data Streams
 * Developer Guide*, and Error Retries and Exponential Backoff in Amazon Web Services in the *Amazon Web Services
 * General Reference*.
 *
 * @see https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
 * @see https://docs.aws.amazon.com/general/latest/gr/api-retries.html
 */
final class ProvisionedThroughputExceededException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
