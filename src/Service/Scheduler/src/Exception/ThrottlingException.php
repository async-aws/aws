<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was denied due to request throttling.
 */
final class ThrottlingException extends ClientException
{
}
