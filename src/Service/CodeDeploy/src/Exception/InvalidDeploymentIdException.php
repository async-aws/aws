<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * At least one of the deployment IDs was specified in an invalid format.
 */
final class InvalidDeploymentIdException extends ClientException
{
}
