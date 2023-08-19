<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The maximum number of tags for an CodeCommit resource has been exceeded.
 */
final class TooManyTagsException extends ClientException
{
}
