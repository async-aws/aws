<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This operation requires a body. Ensure that the body is present and the `Content-Type` header is set.
 */
final class MissingBodyException extends ClientException
{
}
