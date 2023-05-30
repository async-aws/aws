<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because the external key specified by the `XksKeyId` parameter did not meet the
 * configuration requirements for an external key store.
 *
 * The external key must be an AES-256 symmetric key that is enabled and performs encryption and decryption.
 */
final class XksKeyInvalidConfigurationException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
