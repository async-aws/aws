<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The durable execution with the specified name has already been started. Each durable execution name must be unique
 * within the function. Use a different name or check the status of the existing execution.
 */
final class DurableExecutionAlreadyStartedException extends ClientException
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
