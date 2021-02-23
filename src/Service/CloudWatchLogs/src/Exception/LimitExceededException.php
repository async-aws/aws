<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have reached the maximum number of resources that can be created.
 */
final class LimitExceededException extends ClientException
{
}
