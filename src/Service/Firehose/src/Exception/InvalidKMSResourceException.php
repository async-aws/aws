<?php

namespace AsyncAws\Firehose\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Kinesis Data Firehose throws this exception when an attempt to put records or to start or stop delivery stream
 * encryption fails. This happens when the KMS service throws one of the following exception types:
 * `AccessDeniedException`, `InvalidStateException`, `DisabledException`, or `NotFoundException`.
 */
final class InvalidKMSResourceException extends ClientException
{
}
