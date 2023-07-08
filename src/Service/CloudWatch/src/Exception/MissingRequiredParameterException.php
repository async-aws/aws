<?php

namespace AsyncAws\CloudWatch\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An input parameter that is required is missing.
 */
final class MissingRequiredParameterException extends ClientException
{
}
