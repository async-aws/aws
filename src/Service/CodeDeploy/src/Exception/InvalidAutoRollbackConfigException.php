<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The automatic rollback configuration was specified in an invalid format. For example, automatic rollback is enabled,
 * but an invalid triggering event type or no event types were listed.
 */
final class InvalidAutoRollbackConfigException extends ClientException
{
}
