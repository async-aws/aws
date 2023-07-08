<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The Amazon Translate service is temporarily unavailable. Wait a bit and then retry your request.
 */
final class ServiceUnavailableException extends ClientException
{
}
