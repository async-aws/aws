<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A hierarchy can have a maximum of 15 levels. For more information, see Requirements and constraints for parameter
 * names [^1] in the *Amazon Web Services Systems Manager User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-parameter-name-constraints.html
 */
final class HierarchyLevelLimitExceededException extends ClientException
{
}
