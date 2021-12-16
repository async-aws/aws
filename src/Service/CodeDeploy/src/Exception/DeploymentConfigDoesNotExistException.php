<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The deployment configuration does not exist with the IAM user or AWS account.
 */
final class DeploymentConfigDoesNotExistException extends ClientException
{
}
