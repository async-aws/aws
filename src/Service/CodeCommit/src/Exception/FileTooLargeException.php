<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified file exceeds the file size limit for CodeCommit. For more information about limits in CodeCommit, see
 * Quotas [^1] in the *CodeCommit User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/codecommit/latest/userguide/limits.html
 */
final class FileTooLargeException extends ClientException
{
}
