<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Parameter Store doesn't support changing a parameter type in a hierarchy. For example, you can't change a parameter
 * from a `String` type to a `SecureString` type. You must create a new, unique parameter.
 */
final class HierarchyTypeMismatchException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
