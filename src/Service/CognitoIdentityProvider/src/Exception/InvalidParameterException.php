<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This exception is thrown when the Amazon Cognito service encounters an invalid parameter.
 */
final class InvalidParameterException extends ClientException
{
    /**
     * The reason code of the exception.
     *
     * @var string|null
     */
    private $reasonCode;

    public function getReasonCode(): ?string
    {
        return $this->reasonCode;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reasonCode = isset($data['reasonCode']) ? (string) $data['reasonCode'] : null;
    }
}
