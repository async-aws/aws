<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The number of allowed deployments was exceeded.
 */
final class DeploymentLimitExceededException extends ClientException
{
}
