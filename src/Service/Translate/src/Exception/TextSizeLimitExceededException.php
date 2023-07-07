<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The size of the text you submitted exceeds the size limit. Reduce the size of the text or use a smaller document and
 * then retry your request.
 */
final class TextSizeLimitExceededException extends ClientException
{
}
