<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified customer master key (CMK) isn't enabled.
 */
final class KMSDisabledException extends ClientException
{
}
