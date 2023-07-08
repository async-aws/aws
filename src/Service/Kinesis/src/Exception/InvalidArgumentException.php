<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A specified parameter exceeds its restrictions, is not supported, or can't be used. For more information, see the
 * returned message.
 */
final class InvalidArgumentException extends ClientException
{
}
