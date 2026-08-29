<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The computePlatform is invalid. The computePlatform should be `Lambda`, `Server`, or `ECS`.
 */
final class InvalidComputePlatformException extends ClientException
{
}
