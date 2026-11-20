<?php

namespace AsyncAws\Core\Sts\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the context assertion that was provided is invalid. This can happen if the context
 * assertion supplied in the `ProvidedContexts` parameter is malformed, has expired, or does not match a trusted context
 * provider.
 */
final class InvalidContextAssertionException extends ClientException
{
}
