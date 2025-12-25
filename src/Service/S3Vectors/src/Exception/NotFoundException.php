<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified resource can't be found.
 */
final class NotFoundException extends ClientException
{
}
