<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the state of the specified resource is not valid for this request.
 */
final class KmsInvalidStateException extends ClientException
{
}
