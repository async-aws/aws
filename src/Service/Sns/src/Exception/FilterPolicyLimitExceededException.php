<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the number of filter polices in your Amazon Web Services account exceeds the limit. To add more filter
 * polices, submit an Amazon SNS Limit Increase case in the Amazon Web Services Support Center.
 */
final class FilterPolicyLimitExceededException extends ClientException
{
}
