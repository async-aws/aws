<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException as ClientException1;

/**
 * A generic client error. This error usually indicates that the request failed a validation check, such as when a
 * downstream service rejects a configured value.
 */
final class ClientException extends ClientException1
{
}
