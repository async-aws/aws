<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was denied due to request throttling.
 *
 * - The rate of requests per second exceeds the Amazon Web Services KMS request quota for an account and Region.
 * - A burst or sustained high rate of requests to change the state of the same KMS key. This condition is often known
 *   as a "hot key."
 * - Requests for operations on KMS keys in a Amazon Web Services CloudHSM key store might be throttled at a
 *   lower-than-expected rate when the Amazon Web Services CloudHSM cluster associated with the Amazon Web Services
 *   CloudHSM key store is processing numerous commands, including those unrelated to the Amazon Web Services CloudHSM
 *   key store.
 */
final class RequestThrottledException extends ClientException
{
}
