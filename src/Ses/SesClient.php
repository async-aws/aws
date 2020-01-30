<?php

declare(strict_types=1);

namespace AsyncAws\Ses;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\Result;
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
}
