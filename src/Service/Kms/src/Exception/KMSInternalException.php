<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because an internal exception occurred. The request can be retried.
 */
final class KMSInternalException extends ClientException
{
}
