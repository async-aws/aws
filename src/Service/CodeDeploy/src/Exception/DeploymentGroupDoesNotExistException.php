<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The named deployment group with the IAM user or AWS account does not exist.
 */
final class DeploymentGroupDoesNotExistException extends ClientException
{
}
