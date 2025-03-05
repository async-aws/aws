<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The input fails to satisfy the constraints specified by *Amazon Bedrock*. For troubleshooting this error, see
 * ValidationError [^1] in the Amazon Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html#ts-validation-error
 */
final class ValidationException extends ClientException
{
}
