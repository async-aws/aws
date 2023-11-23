<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified entity or resource could not be found.
 */
final class KmsNotFoundException extends ClientException
{
}
