<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Secrets Manager can't encrypt the protected secret text using the provided KMS key. Check that the KMS key is
 * available, enabled, and not in an invalid state. For more information, see Key state: Effect on your KMS key.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
 */
final class EncryptionFailureException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
