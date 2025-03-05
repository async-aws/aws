<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified resource ARN was not found. For troubleshooting this error, see ResourceNotFound [^1] in the Amazon
 * Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html#ts-resource-not-found
 */
final class ResourceNotFoundException extends ClientException
{
}
