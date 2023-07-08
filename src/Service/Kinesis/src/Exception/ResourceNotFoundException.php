<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The requested resource could not be found. The stream might not be specified correctly.
 */
final class ResourceNotFoundException extends ClientException
{
}
