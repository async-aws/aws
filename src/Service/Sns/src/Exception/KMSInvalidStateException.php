<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the state of the specified resource isn't valid for this request. For more
 * information, see How Key State Affects Use of a Customer Master Key in the *AWS Key Management Service Developer
 * Guide*.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
 */
final class KMSInvalidStateException extends ClientException
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
