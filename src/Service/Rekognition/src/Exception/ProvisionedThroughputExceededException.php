<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The number of requests exceeded your throughput limit. If you want to increase this limit, contact Amazon
 * Rekognition.
 */
final class ProvisionedThroughputExceededException extends ClientException
{
}
