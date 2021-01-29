<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * At least one deployment ID must be specified.
 */
final class DeploymentIdRequiredException extends ClientException
{
}
