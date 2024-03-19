<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There was a problem with the Secrets Manager secret used with the request, caused by one of the following conditions:
 *
 * - RDS Data API timed out retrieving the secret.
 * - The secret provided wasn't found.
 * - The secret couldn't be decrypted.
 */
final class SecretsErrorException extends ClientException
{
}
