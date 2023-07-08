<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The permissions policy for the resource is too large. For more information, see Lambda quotas [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/gettingstarted-limits.html
 */
final class PolicyLengthExceededException extends ClientException
{
    /**
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
