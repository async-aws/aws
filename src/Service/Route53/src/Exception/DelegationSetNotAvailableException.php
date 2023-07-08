<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You can create a hosted zone that has the same name as an existing hosted zone (example.com is common), but there is
 * a limit to the number of hosted zones that have the same name. If you get this error, Amazon Route 53 has reached
 * that limit. If you own the domain name and Route 53 generates this error, contact Customer Support.
 */
final class DelegationSetNotAvailableException extends ClientException
{
}
