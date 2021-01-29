<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified receipt handle isn't valid.
 */
final class ReceiptHandleIsInvalidException extends ClientException
{
}
