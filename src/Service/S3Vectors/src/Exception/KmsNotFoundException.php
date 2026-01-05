<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The KMS key can't be found.
 */
final class KmsNotFoundException extends ClientException
{
}
