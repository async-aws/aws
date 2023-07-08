<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because one or more tags are not valid.
 */
final class TagException extends ClientException
{
}
