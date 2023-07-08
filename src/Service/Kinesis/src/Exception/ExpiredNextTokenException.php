<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The pagination token passed to the operation is expired.
 */
final class ExpiredNextTokenException extends ClientException
{
}
