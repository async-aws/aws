<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class AccessDeniedException extends ClientException
{
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->populateResult($response);
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        if (0 < $data->Error->count()) {
            $data = $data->Error;
        }
        $this->message = ($v = $data->Message) ? (string) $v : null;
    }
}
