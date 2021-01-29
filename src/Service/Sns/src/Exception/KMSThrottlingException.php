<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was denied due to request throttling. For more information about throttling, see Limits in the *AWS Key
 * Management Service Developer Guide.*.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/limits.html#requests-per-second
 */
final class KMSThrottlingException extends ClientException
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
