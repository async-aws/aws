<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * You can create a hosted zone that has the same name as an existing hosted zone (example.com is common), but there is
 * a limit to the number of hosted zones that have the same name. If you get this error, Amazon Route 53 has reached
 * that limit. If you own the domain name and Route 53 generates this error, contact Customer Support.
 */
final class DelegationSetNotAvailableException extends ClientException
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
