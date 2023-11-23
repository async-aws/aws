<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was denied due to request throttling.
 */
final class KmsDisabledException extends ClientException
{
}
