<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This operation can't be completed either because the current account has reached the limit on the number of hosted
 * zones or because you've reached the limit on the number of hosted zones that can be associated with a reusable
 * delegation set.
 * For information about default limits, see Limits in the *Amazon Route 53 Developer Guide*.
 * To get the current limit on hosted zones that can be created by an account, see GetAccountLimit.
 * To get the current limit on hosted zones that can be associated with a reusable delegation set, see
 * GetReusableDelegationSetLimit.
 * To request a higher limit, create a case with the AWS Support Center.
 *
 * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/DNSLimitations.html
 * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetAccountLimit.html
 * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetReusableDelegationSetLimit.html
 * @see http://aws.amazon.com/route53-request
 */
final class TooManyHostedZonesException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        if (0 < $data->Error->count()) {
            $data = $data->Error;
        }
        if (null !== $v = (($v = $data->message) ? (string) $v : null)) {
            $this->message = $v;
        }
    }
}
