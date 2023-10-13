<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The requested resource isn't ready. For example, this exception occurs when you call `DetectCustomLabels` with a
 * model version that isn't deployed.
 */
final class ResourceNotReadyException extends ClientException
{
}
