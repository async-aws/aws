<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The maximum number of alarms for a deployment group (10) was exceeded.
 */
final class AlarmsLimitExceededException extends ClientException
{
}
