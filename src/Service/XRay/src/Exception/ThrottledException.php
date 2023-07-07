<?php

namespace AsyncAws\XRay\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request exceeds the maximum number of requests per second.
 */
final class ThrottledException extends ClientException
{
}
