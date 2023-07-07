<?php

namespace AsyncAws\IotData\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified version does not match the version of the document.
 */
final class ConflictException extends ClientException
{
}
