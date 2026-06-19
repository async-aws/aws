<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Lambda couldn't regenerate the SnapStart snapshot for the function. SnapStart-enabled functions periodically
 * regenerate snapshots when their underlying runtime or dependencies change; this regeneration failed. Wait for Lambda
 * to retry, or update the function's configuration to trigger a new snapshot. For more information, see Lambda
 * SnapStart [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/snapstart.html
 */
final class SnapStartRegenerationFailureException extends ClientException
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
