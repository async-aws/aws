<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because it attempted to create resources beyond the current Amazon Web Services account
 * limits. The error message describes the limit exceeded.
 */
final class LimitExceededException extends ClientException
{
}
