<?php

namespace AsyncAws\S3\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A parameter or header in your request isn't valid. For details, see the description of this API operation.
 */
final class InvalidRequestException extends ClientException
{
}
