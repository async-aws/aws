<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Amazon Rekognition experienced a service issue. Try your call again.
 */
final class InternalServerErrorException extends ClientException
{
}
