<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified action violates a limit. For example, `ReceiveMessage` returns this error if the maximum number of in
 * flight messages is reached and `AddPermission` returns this error if the maximum number of permissions for the queue
 * is reached.
 */
final class OverLimitException extends ClientException
{
}
