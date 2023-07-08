<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Can't add more than 50 tags to a topic.
 */
final class TagLimitExceededException extends ClientException
{
}
