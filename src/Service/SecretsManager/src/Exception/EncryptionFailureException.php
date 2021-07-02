<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Secrets Manager can't encrypt the protected secret text using the provided KMS key. Check that the customer master
 * key (CMK) is available, enabled, and not in an invalid state. For more information, see How Key State Affects Use of
 * a Customer Master Key.
 *
 * @see http://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
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
