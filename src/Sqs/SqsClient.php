<?php

declare(strict_types=1);

namespace WorkingTitle\Aws\Sqs;

use WorkingTitle\Aws\AbstractApi;
use WorkingTitle\Aws\ResultPromise;

class SqsClient extends AbstractApi
{
    /**
     * @return ResultPromise<SendMessageResult>
     */
    public function sendMessage(array $header, array $body): ResultPromise
    {
        $authHeader = $this->getAuthHeader();
        $response = $this->httpClient->request($method, $url, [
            'headers' => $headers,
            'body' => $body,
        ]);

        return new ResultPromise($response, SendMessageResult::class);
    }
}
