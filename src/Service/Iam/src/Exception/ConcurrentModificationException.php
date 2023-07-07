<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because multiple requests to change this object were submitted simultaneously. Wait a few
 * minutes and submit your request again.
 */
final class ConcurrentModificationException extends ClientException
{
}
