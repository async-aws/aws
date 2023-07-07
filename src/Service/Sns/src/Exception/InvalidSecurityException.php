<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The credential signature isn't valid. You must use an HTTPS endpoint and sign your request using Signature Version 4.
 */
final class InvalidSecurityException extends ClientException
{
}
