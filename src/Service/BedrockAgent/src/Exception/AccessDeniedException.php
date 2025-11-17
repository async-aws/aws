<?php

namespace AsyncAws\BedrockAgent\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request is denied because of missing access permissions.
 */
final class AccessDeniedException extends ClientException
{
}
