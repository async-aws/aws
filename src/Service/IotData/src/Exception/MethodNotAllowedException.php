<?php

namespace AsyncAws\IotData\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified combination of HTTP verb and URI is not supported.
 */
final class MethodNotAllowedException extends ClientException
{
}
