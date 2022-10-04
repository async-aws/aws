<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The list of triggers for the repository is required, but was not specified.
 */
final class RepositoryTriggersListRequiredException extends ClientException
{
}
