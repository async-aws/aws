<?php

namespace AsyncAws\BedrockAgent\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The number of requests exceeds the limit. Resubmit your request later.
 */
final class ThrottlingException extends ClientException
{
}
