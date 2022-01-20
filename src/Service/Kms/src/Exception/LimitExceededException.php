<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because a quota was exceeded. For more information, see Quotas in the *Key Management
 * Service Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/limits.html
 */
final class LimitExceededException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
