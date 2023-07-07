<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because a specified parameter is not supported or a specified resource is not valid for this
 * operation.
 */
final class UnsupportedOperationException extends ClientException
{
}
