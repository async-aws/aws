<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Either your KMS key policy or API caller does not have the required permissions.
 */
final class KmsAccessDeniedException extends ClientException
{
}
