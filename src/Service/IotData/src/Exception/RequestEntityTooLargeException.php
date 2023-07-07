<?php

namespace AsyncAws\IotData\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The payload exceeds the maximum size allowed.
 */
final class RequestEntityTooLargeException extends ClientException
{
}
