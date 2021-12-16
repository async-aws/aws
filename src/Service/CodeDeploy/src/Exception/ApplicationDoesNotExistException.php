<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The application does not exist with the IAM user or AWS account.
 */
final class ApplicationDoesNotExistException extends ClientException
{
}
