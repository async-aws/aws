<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the specified KMS key cannot decrypt the data. The `KeyId` in a Decrypt request and
 * the `SourceKeyId` in a ReEncrypt request must identify the same KMS key that was used to encrypt the ciphertext.
 */
final class IncorrectKeyException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
