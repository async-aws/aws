<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * An internal server error occurred. For troubleshooting this error, see InternalFailure [^1] in the Amazon Bedrock
 * User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html#ts-internal-failure
 */
final class InternalServerException extends ServerException
{
}
