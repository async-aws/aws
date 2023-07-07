<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The hosted zone you're trying to create already exists. Amazon Route 53 returns this error when a hosted zone has
 * already been created with the specified `CallerReference`.
 */
final class HostedZoneAlreadyExistsException extends ClientException
{
}
