<?php

namespace AsyncAws\CloudWatch\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Parameters were used together that cannot be used together.
 */
final class InvalidParameterCombinationException extends ClientException
{
}
