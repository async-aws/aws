<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Input parameter violated a constraint. Validate your parameter before calling the API operation again.
 */
final class InvalidParameterException extends ClientException
{
}
