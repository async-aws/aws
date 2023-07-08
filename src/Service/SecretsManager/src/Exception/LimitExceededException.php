<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request failed because it would exceed one of the Secrets Manager quotas.
 */
final class LimitExceededException extends ClientException
{
}
