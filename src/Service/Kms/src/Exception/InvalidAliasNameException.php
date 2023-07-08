<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified alias name is not valid.
 */
final class InvalidAliasNameException extends ClientException
{
}
