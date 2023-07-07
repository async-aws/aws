<?php

namespace AsyncAws\TimestreamWrite\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Too many requests were made by a user and they exceeded the service quotas. The request was throttled.
 */
final class ThrottlingException extends ClientException
{
}
