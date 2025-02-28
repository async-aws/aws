<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Your request was denied due to exceeding the account quotas for *Amazon Bedrock*. For troubleshooting this error, see
 * ThrottlingException [^1] in the Amazon Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html#ts-throttling-exception
 */
final class ThrottlingException extends ClientException
{
}
