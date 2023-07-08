<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the marker that specifies where pagination should next begin is not valid.
 */
final class InvalidMarkerException extends ClientException
{
}
