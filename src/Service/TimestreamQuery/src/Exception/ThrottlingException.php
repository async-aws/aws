<?php

namespace AsyncAws\TimestreamQuery\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was throttled due to excessive requests.
 */
final class ThrottlingException extends ClientException
{
}
