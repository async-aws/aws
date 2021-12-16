<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An invalid load balancer name, or no load balancer name, was specified.
 */
final class InvalidLoadBalancerInfoException extends ClientException
{
}
