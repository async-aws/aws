<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have exceeded the number of parameters for this Amazon Web Services account. Delete one or more parameters and
 * try again.
 */
final class ParameterLimitExceededException extends ClientException
{
}
