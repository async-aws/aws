<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You specified more than the maximum number of allowed policies for the parameter. The maximum is 10.
 */
final class PoliciesLimitExceededException extends ClientException
{
}
