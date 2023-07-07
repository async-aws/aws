<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Your request rate is too high. The Amazon Web Services SDKs for DynamoDB automatically retry requests that receive
 * this exception. Your request is eventually successful, unless your retry queue is too large to finish. Reduce the
 * frequency of requests and use exponential backoff. For more information, go to Error Retries and Exponential Backoff
 * [^1] in the *Amazon DynamoDB Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Programming.Errors.html#Programming.Errors.RetryAndBackoff
 */
final class ProvisionedThroughputExceededException extends ClientException
{
}
