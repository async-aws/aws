<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request exceeded a limit. Try your request again.
 */
final class LimitExceededException extends ClientException
{
}
