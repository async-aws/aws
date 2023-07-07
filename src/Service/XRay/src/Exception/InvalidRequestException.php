<?php

namespace AsyncAws\XRay\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request is missing required parameters or has invalid parameters.
 */
final class InvalidRequestException extends ClientException
{
}
