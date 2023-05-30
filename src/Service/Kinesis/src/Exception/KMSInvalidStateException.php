<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the state of the specified resource isn't valid for this request. For more
 * information, see How Key State Affects Use of a Customer Master Key [^1] in the *Amazon Web Services Key Management
 * Service Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
 */
final class KMSInvalidStateException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
