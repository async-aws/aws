<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the state of the specified resource is not valid for this request.
 * For more information about how key state affects the use of a KMS key, see Key state: Effect on your KMS key in the
 * **Key Management Service Developer Guide**.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
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
