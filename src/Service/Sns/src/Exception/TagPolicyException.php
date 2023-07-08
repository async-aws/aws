<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request doesn't comply with the IAM tag policy. Correct your request and then retry it.
 */
final class TagPolicyException extends ClientException
{
}
