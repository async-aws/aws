<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the customer already owns the maximum allowed number of subscriptions.
 */
final class SubscriptionLimitExceededException extends ClientException
{
}
