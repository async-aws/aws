<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Amazon Rekognition is unable to access the S3 object specified in the request.
 */
final class InvalidS3ObjectException extends ClientException
{
}
