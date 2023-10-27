<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified Amazon Web Services KMS key isn't enabled.
 */
final class KMSDisabledException extends ClientException
{
}
