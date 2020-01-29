<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SqsClient extends AbstractApi
{

    public function sendMessage(array $header, array $body): ResultPromise
    {
        $authHeader = $this->getAuthHeader();
        $response = $this->httpClient->request($method, $url, [
            'headers' => $headers,
            'body' => $body,
        ]);

        return ResultPromise::create($response, SendMessageResult::class);
    }
}