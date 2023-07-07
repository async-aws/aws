<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * No hosted zone exists with the ID that you specified.
 */
final class NoSuchHostedZoneException extends ClientException
{
}
