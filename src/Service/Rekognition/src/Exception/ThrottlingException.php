<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Amazon Rekognition is temporarily unable to process the request. Try your call again.
 */
final class ThrottlingException extends ClientException
{
}
