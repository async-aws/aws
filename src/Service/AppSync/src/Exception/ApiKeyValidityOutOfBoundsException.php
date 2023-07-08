<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The API key expiration must be set to a value between 1 and 365 days from creation (for `CreateApiKey`) or from
 * update (for `UpdateApiKey`).
 */
final class ApiKeyValidityOutOfBoundsException extends ClientException
{
}
