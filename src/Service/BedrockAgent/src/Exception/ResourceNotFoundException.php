<?php

namespace AsyncAws\BedrockAgent\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified resource Amazon Resource Name (ARN) was not found. Check the Amazon Resource Name (ARN) and try your
 * request again.
 */
final class ResourceNotFoundException extends ClientException
{
}
