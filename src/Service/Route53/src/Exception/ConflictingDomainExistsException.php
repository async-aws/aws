<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The cause of this error depends on the operation that you're performing:.
 *
 * - **Create a public hosted zone:** Two hosted zones that have the same name or that have a parent/child relationship
 *   (example.com and test.example.com) can't have any common name servers. You tried to create a hosted zone that has
 *   the same name as an existing hosted zone or that's the parent or child of an existing hosted zone, and you
 *   specified a delegation set that shares one or more name servers with the existing hosted zone. For more
 *   information, see CreateReusableDelegationSet [^1].
 * - **Create a private hosted zone:** A hosted zone with the specified name already exists and is already associated
 *   with the Amazon VPC that you specified.
 * - **Associate VPCs with a private hosted zone:** The VPC that you specified is already associated with another hosted
 *   zone that has the same name.
 *
 * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateReusableDelegationSet.html
 */
final class ConflictingDomainExistsException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        if (0 < $data->Error->count()) {
            $data = $data->Error;
        }
        if (null !== $v = (($v = $data->message) ? (string) $v : null)) {
            $this->message = $v;
        }
    }
}
