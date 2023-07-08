<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified policy is not syntactically or semantically correct.
 */
final class MalformedPolicyDocumentException extends ClientException
{
}
