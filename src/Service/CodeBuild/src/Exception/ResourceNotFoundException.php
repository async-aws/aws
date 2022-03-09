<?php

namespace AsyncAws\CodeBuild\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified Amazon Web Services resource cannot be found.
 */
final class ResourceNotFoundException extends ClientException
{
}
