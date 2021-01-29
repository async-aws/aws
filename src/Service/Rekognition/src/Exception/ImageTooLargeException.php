<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The input image size exceeds the allowed limit. For more information, see Limits in Amazon Rekognition in the Amazon
 * Rekognition Developer Guide.
 */
final class ImageTooLargeException extends ClientException
{
}
