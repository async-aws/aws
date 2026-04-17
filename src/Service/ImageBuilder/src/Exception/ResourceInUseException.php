<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource that you are trying to operate on is currently in use. Review the message details and retry later.
 */
final class ResourceInUseException extends ClientException
{
}
