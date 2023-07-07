<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The VPC ID that you specified either isn't a valid ID or the current account is not authorized to access this VPC.
 */
final class InvalidVPCIdException extends ClientException
{
}
