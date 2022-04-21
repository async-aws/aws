<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified file exceeds the file size limit for AWS CodeCommit. For more information about limits in AWS
 * CodeCommit, see AWS CodeCommit User Guide.
 *
 * @see https://docs.aws.amazon.com/codecommit/latest/userguide/limits.html
 */
final class FileTooLargeException extends ClientException
{
}
