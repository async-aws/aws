<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Another modification is in progress at this time and it must complete before you can make your change.
 */
final class ConcurrentModificationException extends ClientException
{
}
