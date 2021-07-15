<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * A hierarchy can have a maximum of 15 levels. For more information, see Requirements and constraints for parameter
 * names in the *Amazon Web Services Systems Manager User Guide*.
 *
 * @see https://docs.aws.amazon.com/systems-manager/latest/userguide/sysman-parameter-name-constraints.html
 */
final class HierarchyLevelLimitExceededException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
