<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The RevisionId provided does not match the latest RevisionId for the Lambda function or alias.
 *
 * - **For AddPermission and RemovePermission API operations:** Call `GetPolicy` to retrieve the latest RevisionId for
 *   your resource.
 * - **For all other API operations:** Call `GetFunction` or `GetAlias` to retrieve the latest RevisionId for your
 *   resource.
 */
final class PreconditionFailedException extends ClientException
{
    /**
     * The exception type.
     *
     * @var string|null
     */
    private $type;

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
    }
}
