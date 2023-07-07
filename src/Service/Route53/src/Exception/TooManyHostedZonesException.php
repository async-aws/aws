<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This operation can't be completed either because the current account has reached the limit on the number of hosted
 * zones or because you've reached the limit on the number of hosted zones that can be associated with a reusable
 * delegation set.
 *
 * For information about default limits, see Limits [^1] in the *Amazon Route 53 Developer Guide*.
 *
 * To get the current limit on hosted zones that can be created by an account, see GetAccountLimit [^2].
 *
 * To get the current limit on hosted zones that can be associated with a reusable delegation set, see
 * GetReusableDelegationSetLimit [^3].
 *
 * To request a higher limit, create a case [^4] with the Amazon Web Services Support Center.
 *
 * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/DNSLimitations.html
 * [^2]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetAccountLimit.html
 * [^3]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetReusableDelegationSetLimit.html
 * [^4]: http://aws.amazon.com/route53-request
 */
final class TooManyHostedZonesException extends ClientException
{
}
