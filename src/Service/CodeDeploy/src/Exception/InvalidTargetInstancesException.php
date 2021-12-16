<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The target instance configuration is invalid. Possible causes include:.
 *
 * - Configuration data for target instances was entered for an in-place deployment.
 * - The limit of 10 tags for a tag type was exceeded.
 * - The combined length of the tag names exceeded the limit.
 * - A specified tag is not currently applied to any instances.
 */
final class InvalidTargetInstancesException extends ClientException
{
}
