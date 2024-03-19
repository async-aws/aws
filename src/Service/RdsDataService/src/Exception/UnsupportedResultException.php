<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There was a problem with the result because of one of the following conditions:
 *
 * - It contained an unsupported data type.
 * - It contained a multidimensional array.
 * - The size was too large.
 */
final class UnsupportedResultException extends ClientException
{
}
