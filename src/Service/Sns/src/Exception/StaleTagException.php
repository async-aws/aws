<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A tag has been added to a resource with the same ARN as a deleted resource. Wait a short while and then retry the
 * operation.
 */
final class StaleTagException extends ClientException
{
}
