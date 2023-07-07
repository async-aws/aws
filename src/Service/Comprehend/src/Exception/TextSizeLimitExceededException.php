<?php

namespace AsyncAws\Comprehend\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The size of the input text exceeds the limit. Use a smaller document.
 */
final class TextSizeLimitExceededException extends ClientException
{
}
