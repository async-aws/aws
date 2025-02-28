<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The model specified in the request is not ready to serve inference requests. The AWS SDK will automatically retry the
 * operation up to 5 times. For information about configuring automatic retries, see Retry behavior [^1] in the *AWS
 * SDKs and Tools* reference guide.
 *
 * [^1]: https://docs.aws.amazon.com/sdkref/latest/guide/feature-retry-behavior.html
 */
final class ModelNotReadyException extends ClientException
{
}
