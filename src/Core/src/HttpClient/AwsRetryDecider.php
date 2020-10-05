<?php

namespace AsyncAws\Core\HttpClient;

use AsyncAws\Core\AwsError\AwsErrorFactory;
use AsyncAws\Core\Exception\ParseResponse;
use Symfony\Component\HttpClient\Retry\RetryDeciderInterface;

/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class AwsRetryDecider implements RetryDeciderInterface
{
    public function shouldRetry(
        string $requestMethod,
        string $requestUrl,
        array $requestOptions,
        int $responseStatusCode,
        array $responseHeaders,
        ?string $responseContent
    ): ?bool {
        if (\in_array($responseStatusCode, [423, 425, 429, 500, 502, 503, 504, 507, 510], true)) {
            return true;
        }
        if (
            400 !== $responseStatusCode
            || (int) ($responseHeaders['content-length'][0] ?? '0') > 16384 // prevent downloading payload > 16KiB
        ) {
            return false;
        }

        if (null === $responseContent) {
            return null; // null mean no decision taken and need to be called again with the body
        }

        try {
            $error = AwsErrorFactory::createFromContent($responseContent, $responseHeaders);
        } catch (ParseResponse $e) {
            return false;
        }

        return \in_array($error->getCode(), [
            'RequestLimitExceeded',
            'Throttling',
            'ThrottlingException',
            'ThrottledException',
            'LimitExceededException',
            'PriorRequestNotComplete',
            'ProvisionedThroughputExceededException',
            'RequestThrottled',
            'SlowDown',
            'BandwidthLimitExceeded',
            'RequestThrottledException',
            'RetryableThrottlingException',
            'TooManyRequestsException',
            'IDPCommunicationError',
            'EC2ThrottledException',
            'TransactionInProgressException',
        ], true);
    }
}
