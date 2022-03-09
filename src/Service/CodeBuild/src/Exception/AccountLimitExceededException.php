<?php

namespace AsyncAws\CodeBuild\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An Amazon Web Services service limit was exceeded for the calling Amazon Web Services account.
 */
final class AccountLimitExceededException extends ClientException
{
}
