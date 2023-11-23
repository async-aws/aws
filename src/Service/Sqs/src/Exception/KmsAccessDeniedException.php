<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The caller doesn't have the required KMS access.
 */
final class KmsAccessDeniedException extends ClientException
{
}
