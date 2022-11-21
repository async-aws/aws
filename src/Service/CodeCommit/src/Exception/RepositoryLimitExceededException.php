<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A repository resource limit was exceeded.
 */
final class RepositoryLimitExceededException extends ClientException
{
}
