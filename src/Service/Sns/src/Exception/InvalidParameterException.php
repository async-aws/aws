<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that a request parameter does not comply with the associated constraints.
 */
final class InvalidParameterException extends ClientException
{
}
