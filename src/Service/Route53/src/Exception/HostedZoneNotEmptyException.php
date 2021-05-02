<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The hosted zone contains resource records that are not SOA or NS records.
 */
final class HostedZoneNotEmptyException extends ClientException
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
