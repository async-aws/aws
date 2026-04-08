<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The Lambda function couldn't make a network connection to the configured S3 Files access point.
 */
final class S3FilesMountConnectivityException extends ClientException
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
