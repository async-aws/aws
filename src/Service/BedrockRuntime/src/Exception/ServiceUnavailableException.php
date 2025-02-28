<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The service isn't currently available. For troubleshooting this error, see ServiceUnavailable [^1] in the Amazon
 * Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html#ts-service-unavailable
 */
final class ServiceUnavailableException extends ServerException
{
}
