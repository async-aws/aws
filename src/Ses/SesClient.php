<?php

declare(strict_types=1);

namespace AsyncAws\Ses;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Ses\Result\SendEmailResult;

class SesClient extends AbstractApi
{
    public function sendEmail(array $body, array $headers = []): SendEmailResult
    {
        $response = $this->getResponse('POST', $body, $headers);

        return new SendEmailResult($response);
    }

    protected function getServiceCode(): string
    {
        return 'ses';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
