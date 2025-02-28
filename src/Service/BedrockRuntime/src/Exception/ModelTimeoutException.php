<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request took too long to process. Processing time exceeded the model timeout length.
 */
final class ModelTimeoutException extends ClientException
{
}
