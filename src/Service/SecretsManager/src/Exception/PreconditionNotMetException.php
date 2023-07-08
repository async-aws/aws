<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request failed because you did not complete all the prerequisite steps.
 */
final class PreconditionNotMetException extends ClientException
{
}
