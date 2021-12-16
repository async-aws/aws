<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An invalid fileExistsBehavior option was specified to determine how AWS CodeDeploy handles files or directories that
 * already exist in a deployment target location, but weren't part of the previous successful deployment. Valid values
 * include "DISALLOW," "OVERWRITE," and "RETAIN.".
 */
final class InvalidFileExistsBehaviorException extends ClientException
{
}
