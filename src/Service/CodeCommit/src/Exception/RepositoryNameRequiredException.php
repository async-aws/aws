<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A repository name is required, but was not specified.
 */
final class RepositoryNameRequiredException extends ClientException
{
}
