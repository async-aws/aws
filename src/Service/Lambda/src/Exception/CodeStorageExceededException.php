<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * You have exceeded your maximum total code size per account. Learn more.
 *
 * @see https://docs.aws.amazon.com/lambda/latest/dg/limits.html
 */
final class CodeStorageExceededException extends ClientException
{
    /**
     * The exception type.
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
        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
