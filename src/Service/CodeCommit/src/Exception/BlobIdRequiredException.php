<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A blob ID is required, but was not specified.
 */
final class BlobIdRequiredException extends ClientException
{
}
