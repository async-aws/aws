<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * From the Decrypt or ReEncrypt operation, the request was rejected because the specified ciphertext, or additional
 * authenticated data incorporated into the ciphertext, such as the encryption context, is corrupted, missing, or
 * otherwise invalid.
 * From the ImportKeyMaterial operation, the request was rejected because KMS could not decrypt the encrypted (wrapped)
 * key material.
 */
final class InvalidCiphertextException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
