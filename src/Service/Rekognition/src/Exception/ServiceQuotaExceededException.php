<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The size of the collection exceeds the allowed limit. For more information, see Limits in Amazon Rekognition in the
 * Amazon Rekognition Developer Guide.
 */
final class ServiceQuotaExceededException extends ClientException
{
}
