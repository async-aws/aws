<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\AwsError\AwsError;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This operation requires a body. Ensure that the body is present and the `Content-Type` header is set.
 */
final class MissingBodyException extends ClientException
{
    public function __construct(ResponseInterface $response, ?AwsError $awsError)
    {
        parent::__construct($response, $awsError);
        $this->populateResult($response);
    }

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
