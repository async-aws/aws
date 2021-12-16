<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The service role ARN was specified in an invalid format. Or, if an Auto Scaling group was specified, the specified
 * service role does not grant the appropriate permissions to Amazon EC2 Auto Scaling.
 */
final class InvalidRoleException extends ClientException
{
}
