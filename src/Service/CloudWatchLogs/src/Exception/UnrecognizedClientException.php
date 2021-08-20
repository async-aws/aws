<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The most likely cause is an invalid Amazon Web Services access key ID or secret key.
 */
final class UnrecognizedClientException extends ClientException
{
}
