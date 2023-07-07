<?php

namespace AsyncAws\Iot\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The rate exceeds the limit.
 */
final class ThrottlingException extends ClientException
{
}
