<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because an invalid or out-of-range value was supplied for an input parameter.
 */
final class InvalidInputException extends ClientException
{
}
