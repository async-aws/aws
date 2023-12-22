<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The Secrets Manager secret used with the request isn't valid.
 */
final class InvalidSecretException extends ClientException
{
}
