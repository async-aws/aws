<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * An internal AppSync error occurred. Try your request again.
 */
final class InternalFailureException extends ServerException
{
}
