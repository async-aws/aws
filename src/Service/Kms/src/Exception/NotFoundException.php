<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified entity or resource could not be found.
 */
final class NotFoundException extends ClientException
{
}
