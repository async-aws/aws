<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The format of the alarm configuration is invalid. Possible causes include:.
 *
 * - The alarm list is null.
 * - The alarm object is null.
 * - The alarm name is empty or null or exceeds the limit of 255 characters.
 * - Two alarms with the same name have been specified.
 * - The alarm configuration is enabled, but the alarm list is empty.
 */
final class InvalidAlarmConfigException extends ClientException
{
}
