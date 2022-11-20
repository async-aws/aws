<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified tag is not valid. Key names cannot be prefixed with aws:.
 */
final class InvalidSystemTagUsageException extends ClientException
{
}
