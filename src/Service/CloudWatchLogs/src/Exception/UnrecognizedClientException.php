<?php

namespace AsyncAws\CloudWatchLogs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The most likely cause is an Amazon Web Services access key ID or secret key that's not valid.
 */
final class UnrecognizedClientException extends ClientException
{
}
