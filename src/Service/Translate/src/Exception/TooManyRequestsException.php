<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have made too many requests within a short period of time. Wait for a short time and then try your request again.
 */
final class TooManyRequestsException extends ClientException
{
}
