<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\AwsError\AwsError;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The specified distribution does not exist.
 */
final class NoSuchDistributionException extends ClientException
{
    public function __construct(ResponseInterface $response, ?AwsError $awsError)
    {
        parent::__construct($response, $awsError);
        $this->populateResult($response);
    }

    private function populateResult(ResponseInterface $response): void
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
