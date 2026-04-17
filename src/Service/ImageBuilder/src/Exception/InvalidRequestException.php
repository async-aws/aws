<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have requested an action that that the service doesn't support.
 */
final class InvalidRequestException extends ClientException
{
}
