<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified entity or resource can't be found.
 */
final class KMSNotFoundException extends ClientException
{
}
