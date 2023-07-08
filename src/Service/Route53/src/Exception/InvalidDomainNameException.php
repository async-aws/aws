<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified domain name is not valid.
 */
final class InvalidDomainNameException extends ClientException
{
}
