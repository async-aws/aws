<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have exceeded the maximum number of allowable InProgress invalidation batch requests, or invalidation objects.
 */
final class TooManyInvalidationsInProgressException extends ClientException
{
}
