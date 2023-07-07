<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource policy has syntax errors.
 */
final class MalformedPolicyDocumentException extends ClientException
{
}
