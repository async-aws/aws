<?php

namespace AsyncAws\LocationService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was denied because of request throttling.
 */
final class ThrottlingException extends ClientException
{
}
