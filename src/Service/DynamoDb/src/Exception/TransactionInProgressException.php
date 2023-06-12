<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The transaction with the given request token is already in progress.
 *
 * Recommended Settings
 *
 * > This is a general recommendation for handling the `TransactionInProgressException`. These settings help ensure that
 * > the client retries will trigger completion of the ongoing `TransactWriteItems` request.
 *
 * - Set `clientExecutionTimeout` to a value that allows at least one retry to be processed after 5 seconds have elapsed
 *   since the first attempt for the `TransactWriteItems` operation.
 * - Set `socketTimeout` to a value a little lower than the `requestTimeout` setting.
 * - `requestTimeout` should be set based on the time taken for the individual retries of a single HTTP request for your
 *   use case, but setting it to 1 second or higher should work well to reduce chances of retries and
 *   `TransactionInProgressException` errors.
 * - Use exponential backoff when retrying and tune backoff if needed.
 *
 * Assuming default retry policy [^1], example timeout settings based on the guidelines above are as follows:
 *
 * Example timeline:
 *
 * - 0-1000 first attempt
 * - 1000-1500 first sleep/delay (default retry policy uses 500 ms as base delay for 4xx errors)
 * - 1500-2500 second attempt
 * - 2500-3500 second sleep/delay (500 * 2, exponential backoff)
 * - 3500-4500 third attempt
 * - 4500-6500 third sleep/delay (500 * 2^2)
 * - 6500-7500 fourth attempt (this can trigger inline recovery since 5 seconds have elapsed since the first attempt
 *   reached TC)
 *
 * [^1]: https://github.com/aws/aws-sdk-java/blob/fd409dee8ae23fb8953e0bb4dbde65536a7e0514/aws-java-sdk-core/src/main/java/com/amazonaws/retry/PredefinedRetryPolicies.java#L97
 */
final class TransactionInProgressException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
