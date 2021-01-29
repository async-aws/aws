<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified receipt handle isn't valid for the current version.
 */
final class InvalidIdFormatException extends ClientException
{
}
