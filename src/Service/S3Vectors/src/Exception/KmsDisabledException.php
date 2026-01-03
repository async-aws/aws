<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified Amazon Web Services KMS key isn't enabled.
 */
final class KmsDisabledException extends ClientException
{
}
