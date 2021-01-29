<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified message isn't in flight.
 */
final class MessageNotInflightException extends ClientException
{
}
