<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected for one of the following reasons:.
 *
 * - The `KeyUsage` value of the KMS key is incompatible with the API operation.
 * - The encryption algorithm or signing algorithm specified for the operation is incompatible with the type of key
 *   material in the KMS key `(KeySpec`).
 *
 * For encrypting, decrypting, re-encrypting, and generating data keys, the `KeyUsage` must be `ENCRYPT_DECRYPT`. For
 * signing and verifying, the `KeyUsage` must be `SIGN_VERIFY`. To find the `KeyUsage` of a KMS key, use the DescribeKey
 * operation.
 * To find the encryption or signing algorithms supported for a particular KMS key, use the DescribeKey operation.
 */
final class InvalidKeyUsageException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
