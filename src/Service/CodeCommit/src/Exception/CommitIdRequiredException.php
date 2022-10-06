<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A commit ID was not specified.
 */
final class CommitIdRequiredException extends ClientException
{
}
