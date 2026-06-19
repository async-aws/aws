<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The Lambda function doesn't support the invocation mode requested. For example, calling `Invoke` with
 * `InvocationType=RequestResponse` on a function configured for asynchronous-only invocation, or vice versa. For more
 * information about invocation types, see Invoking Lambda functions [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-options.html
 */
final class ModeNotSupportedException extends ClientException
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
