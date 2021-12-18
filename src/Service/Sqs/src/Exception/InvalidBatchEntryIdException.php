<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The `Id` of a batch entry in a batch request doesn't abide by the specification.
 */
final class InvalidBatchEntryIdException extends ClientException
{
}
