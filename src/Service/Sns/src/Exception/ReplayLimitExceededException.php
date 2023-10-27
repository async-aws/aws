<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the request parameter has exceeded the maximum number of concurrent message replays.
 */
final class ReplayLimitExceededException extends ClientException
{
}
