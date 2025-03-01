<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The parameter couldn't be found. Verify the name and try again.
 *
 * > For the `DeleteParameter` and `GetParameter` actions, if the specified parameter doesn't exist, the
 * > `ParameterNotFound` exception is *not* recorded in CloudTrail event logs.
 */
final class ParameterNotFoundException extends ClientException
{
}
