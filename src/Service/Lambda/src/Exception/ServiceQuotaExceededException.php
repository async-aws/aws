<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request would exceed a service quota. For more information about Lambda service quotas, see Lambda quotas [^1].
 * To request a quota increase, see Requesting a quota increase [^2] in the *Service Quotas User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/gettingstarted-limits.html
 * [^2]: https://docs.aws.amazon.com/servicequotas/latest/userguide/request-quota-increase.html
 */
final class ServiceQuotaExceededException extends ClientException
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
