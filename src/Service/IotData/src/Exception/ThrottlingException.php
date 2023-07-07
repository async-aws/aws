<?php

namespace AsyncAws\IotData\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The rate exceeds the limit.
 */
final class ThrottlingException extends ClientException
{
}
