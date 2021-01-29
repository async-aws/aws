<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A call was submitted that is not supported for the specified deployment type.
 */
final class UnsupportedActionForDeploymentTypeException extends ClientException
{
}
