<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException as ClientException1;

/**
 * These errors are usually caused by a client action, such as using an action or resource on behalf of a user that
 * doesn't have permissions to use the action or resource, or specifying an invalid resource identifier.
 */
final class ClientException extends ClientException1
{
}
