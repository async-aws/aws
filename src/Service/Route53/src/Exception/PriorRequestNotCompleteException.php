<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * If Amazon Route 53 can't process a request before the next request arrives, it will reject subsequent requests for
 * the same hosted zone and return an `HTTP 400 error` (`Bad request`). If Route 53 returns this error repeatedly for
 * the same request, we recommend that you wait, in intervals of increasing duration, before you try the request again.
 */
final class PriorRequestNotCompleteException extends ClientException
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
