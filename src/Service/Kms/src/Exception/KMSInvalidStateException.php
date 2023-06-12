<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the state of the specified resource is not valid for this request.
 *
 * This exceptions means one of the following:
 *
 * - The key state of the KMS key is not compatible with the operation.
 *
 *   To find the key state, use the DescribeKey operation. For more information about which key states are compatible
 *   with each KMS operation, see Key states of KMS keys [^1] in the **Key Management Service Developer Guide**.
 * - For cryptographic operations on KMS keys in custom key stores, this exception represents a general failure with
 *   many possible causes. To identify the cause, see the error message that accompanies the exception.
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
